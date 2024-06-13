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
        Schema::table('houses', function (Blueprint $table) {
            $table->foreign(['rentalNo'], 'houses_ibfk_1')->references(['rentalNo'])->on('rentals')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['structureName'], 'houses_ibfk_2')->references(['structureName'])->on('structures')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['houseType'], 'houses_ibfk_3')->references(['houseType'])->on('housetypes')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            $table->dropForeign('houses_ibfk_1');
            $table->dropForeign('houses_ibfk_2');
            $table->dropForeign('houses_ibfk_3');
        });
    }
};
