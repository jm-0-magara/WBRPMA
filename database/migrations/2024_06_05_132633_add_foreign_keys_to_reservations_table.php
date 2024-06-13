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
        Schema::table('reservations', function (Blueprint $table) {
            $table->foreign(['houseNo'], 'reservations_ibfk_1')->references(['houseNo'])->on('houses')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['rentalNo'], 'reservations_ibfk_2')->references(['rentalNo'])->on('houses')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign('reservations_ibfk_1');
            $table->dropForeign('reservations_ibfk_2');
        });
    }
};
