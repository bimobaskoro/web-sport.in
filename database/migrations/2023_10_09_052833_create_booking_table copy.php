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
        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
            $table->string('name'); // Field for user's name
            $table->string('field_name'); // Field for product's name
            $table->string('type'); // Field for product's name
            $table->string('location'); // Field for product's location
            $table->date('booking_date'); // Field for booking date
            $table->integer('start_booking_hour'); // Field for booking hour
            $table->integer('finish_booking_hour'); // Field for booking hour
            $table->integer('price'); // Field for booking hour
            $table->integer('total_price');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('product')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
