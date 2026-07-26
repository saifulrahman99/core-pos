<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();

            // General
            $table->string('name');
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();

            // Contact
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();

            // Address
            $table->text('address')->nullable();
            $table->string('google_maps_url')->nullable();

            // Business
            $table->string('currency', 10)->default('IDR');
            $table->string('timezone', 50)->default('Asia/Jakarta');
            $table->string('language', 10)->default('id');

            // Receipt
            $table->string('receipt_header')->nullable();
            $table->string('receipt_footer')->nullable();

            // Operational
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
