<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class BookingPhotoCompressor
{
    private const MAX_DIMENSION = 2000;

    private const MAX_PIXELS = 20_000_000;

    private const WEBP_QUALITY = 82;

    /**
     * @return array{contents: string, extension: string}
     */
    public function compress(UploadedFile $file): array
    {
        $original = file_get_contents($file->getRealPath());

        if ($original === false) {
            throw new RuntimeException('The uploaded photo could not be read.');
        }

        if (! extension_loaded('gd') || ! function_exists('imagewebp')) {
            return ['contents' => $original, 'extension' => $this->extensionForMime($file->getMimeType())];
        }

        $imageInfo = getimagesize($file->getRealPath());

        if ($imageInfo === false || ! isset($imageInfo[0], $imageInfo[1], $imageInfo['mime'])) {
            throw new RuntimeException('The uploaded photo could not be decoded.');
        }

        [$width, $height] = $imageInfo;

        if ($width * $height > self::MAX_PIXELS) {
            throw ValidationException::withMessages([
                'photo' => 'The uploaded photo dimensions are too large to process.',
            ]);
        }

        $source = match ($imageInfo['mime']) {
            'image/jpeg' => imagecreatefromjpeg($file->getRealPath()),
            'image/png' => imagecreatefrompng($file->getRealPath()),
            'image/webp' => imagecreatefromwebp($file->getRealPath()),
            default => false,
        };

        if (! $source instanceof \GdImage) {
            throw new RuntimeException('The uploaded photo could not be decoded.');
        }

        try {
            if ($imageInfo['mime'] === 'image/jpeg' && function_exists('exif_read_data')) {
                $source = $this->applyExifOrientation($source, $file->getRealPath());
            }

            $sourceWidth = imagesx($source);
            $sourceHeight = imagesy($source);
            $scale = min(1, self::MAX_DIMENSION / max($sourceWidth, $sourceHeight));
            $targetWidth = max(1, (int) round($sourceWidth * $scale));
            $targetHeight = max(1, (int) round($sourceHeight * $scale));
            $resized = imagecreatetruecolor($targetWidth, $targetHeight);

            if ($resized === false) {
                throw new RuntimeException('The uploaded photo could not be resized.');
            }

            try {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);

                if (! imagecopyresampled(
                    $resized,
                    $source,
                    0,
                    0,
                    0,
                    0,
                    $targetWidth,
                    $targetHeight,
                    $sourceWidth,
                    $sourceHeight,
                )) {
                    throw new RuntimeException('The uploaded photo could not be resized.');
                }

                $stream = fopen('php://temp', 'w+b');

                if ($stream === false) {
                    throw new RuntimeException('A temporary photo buffer could not be created.');
                }

                try {
                    if (! imagewebp($resized, $stream, self::WEBP_QUALITY)) {
                        throw new RuntimeException('The uploaded photo could not be compressed.');
                    }

                    rewind($stream);
                    $compressed = stream_get_contents($stream);

                    if ($compressed === false) {
                        throw new RuntimeException('The compressed photo could not be read.');
                    }
                } finally {
                    fclose($stream);
                }
            } finally {
                imagedestroy($resized);
            }

            return strlen($compressed) < strlen($original)
                ? ['contents' => $compressed, 'extension' => 'webp']
                : ['contents' => $original, 'extension' => $this->extensionForMime($imageInfo['mime'])];
        } finally {
            imagedestroy($source);
        }
    }

    private function extensionForMime(string $mime): string
    {
        return match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => throw new RuntimeException('The uploaded photo format is not supported.'),
        };
    }

    private function applyExifOrientation(\GdImage $image, string $path): \GdImage
    {
        $exif = exif_read_data($path);
        $orientation = $exif['Orientation'] ?? 1;
        $rotation = match ($orientation) {
            3 => 180,
            6 => -90,
            8 => 90,
            default => null,
        };

        if ($rotation === null) {
            return $image;
        }

        $rotated = imagerotate($image, $rotation, 0);

        if ($rotated === false) {
            throw new RuntimeException('The uploaded photo orientation could not be corrected.');
        }

        imagedestroy($image);

        return $rotated;
    }
}
