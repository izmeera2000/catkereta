<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paint_booking_id')->constrained()->cascadeOnDelete();
            $table->string('stage', 20);
            $table->string('path');
            $table->string('caption')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['paint_booking_id', 'stage']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_photos');
    }
};
