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
        Schema::table('deletedtenants', function (Blueprint $table) {
            $table->foreign(['houseNo'], 'deletedtenants_ibfk_1')->references(['houseNo'])->on('houses')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['rentalNo'], 'deletedtenants_ibfk_2')->references(['rentalNo'])->on('houses')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deletedtenants', function (Blueprint $table) {
            $table->dropForeign('deletedtenants_ibfk_1');
            $table->dropForeign('deletedtenants_ibfk_2');
        });
    }
};
