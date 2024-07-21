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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_users_id');
            $table->foreign('app_users_id')->references('id')->on('app_users')->onDelete('cascade');
            $table->decimal('total_price')->default(0.00);
            $table->foreignid('addresses_id')->references('id')->on('addresses')->onDelete('cascade');
            $table->foreignid('delevery_time_id')->references('id')->on('delevery_times')->onDelete('cascade');
            $table->string('payment_method');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
