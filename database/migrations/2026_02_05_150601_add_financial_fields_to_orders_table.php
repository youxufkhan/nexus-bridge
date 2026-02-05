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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total_amount', 15, 2)->nullable()->after('status');
            $table->decimal('total_tax', 15, 2)->default(0)->after('total_amount');
            $table->decimal('total_shipping', 15, 2)->default(0)->after('total_tax');
            $table->string('currency', 3)->default('USD')->after('total_shipping');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['total_amount', 'total_tax', 'total_shipping', 'currency']);
        });
    }
};
