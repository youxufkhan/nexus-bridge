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
            $table->foreignId('agency_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('integration_connection_id')->constrained()->cascadeOnDelete();
            $table->string('external_order_id');
            $table->string('status')->default('NEW');
            $table->jsonb('customer_data')->nullable(); // {name, email, phone, shipping_address}
            $table->jsonb('financials')->nullable(); // {currency, total, tax, shipping}
            $table->unique(['integration_connection_id', 'external_order_id']);
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
