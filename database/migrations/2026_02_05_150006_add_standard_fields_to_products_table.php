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
        Schema::table('products', function (Blueprint $table) {
            $table->string('upc')->nullable()->after('name');
            $table->string('gtin')->nullable()->after('upc');
            $table->decimal('base_price', 15, 2)->nullable()->after('gtin');
            $table->string('base_currency', 3)->default('USD')->after('base_price');
            $table->string('status')->default('active')->after('base_currency');
            $table->string('brand')->nullable()->after('status');
            $table->string('category')->nullable()->after('brand');
            $table->text('description')->nullable()->after('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['upc', 'gtin', 'base_price', 'base_currency', 'status', 'brand', 'category', 'description']);
        });
    }
};
