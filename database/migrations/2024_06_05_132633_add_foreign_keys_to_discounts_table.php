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
        Schema::table('discounts', function (Blueprint $table) {
            $table->foreign(['houseNo'], 'discounts_ibfk_1')->references(['houseNo'])->on('houses')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['rentalNo'], 'discounts_ibfk_2')->references(['rentalNo'])->on('houses')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['tenantNo'], 'discounts_ibfk_3')->references(['tenantNo'])->on('tenants')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropForeign('discounts_ibfk_1');
            $table->dropForeign('discounts_ibfk_2');
            $table->dropForeign('discounts_ibfk_3');
        });
    }
};
