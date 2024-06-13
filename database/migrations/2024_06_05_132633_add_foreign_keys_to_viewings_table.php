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
        Schema::table('viewings', function (Blueprint $table) {
            $table->foreign(['houseNo'], 'viewings_ibfk_1')->references(['houseNo'])->on('houses')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['rentalNo'], 'viewings_ibfk_2')->references(['rentalNo'])->on('houses')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('viewings', function (Blueprint $table) {
            $table->dropForeign('viewings_ibfk_1');
            $table->dropForeign('viewings_ibfk_2');
        });
    }
};
