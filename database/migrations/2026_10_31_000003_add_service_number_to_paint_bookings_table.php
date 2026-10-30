<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paint_bookings', function (Blueprint $table) {
            $table->string('service_number')->nullable()->unique();
        });

        DB::table('paint_bookings')
            ->select('id')
            ->orderBy('id')
            ->chunkById(100, function ($bookings): void {
                foreach ($bookings as $booking) {
                    do {
                        $serviceNumber = 'SRV-'.Str::upper(Str::random(12));
                    } while (DB::table('paint_bookings')->where('service_number', $serviceNumber)->exists());

                    DB::table('paint_bookings')
                        ->where('id', $booking->id)
                        ->update(['service_number' => $serviceNumber]);
                }
            });

        Schema::table('paint_bookings', function (Blueprint $table) {
            $table->string('service_number')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('paint_bookings', function (Blueprint $table) {
            $table->dropUnique(['service_number']);
            $table->dropColumn('service_number');
        });
    }
};
