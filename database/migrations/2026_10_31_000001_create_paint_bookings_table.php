<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paint_bookings', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone', 40);
            $table->string('vehicle_make', 80);
            $table->string('vehicle_model', 80);
            $table->unsignedSmallInteger('vehicle_year')->nullable();
            $table->string('vehicle_color', 80)->nullable();
            $table->string('service_type', 80);
            $table->text('description')->nullable();
            $table->date('preferred_date')->nullable();
            $table->string('status', 30)->default('requested')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paint_bookings');
    }
};
