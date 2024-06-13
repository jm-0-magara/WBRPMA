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
        Schema::table('waterdetails', function (Blueprint $table) {
            $table->foreign(['houseNo'], 'waterdetails_ibfk_1')->references(['houseNo'])->on('houses')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['rentalNo'], 'waterdetails_ibfk_2')->references(['rentalNo'])->on('houses')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('waterdetails', function (Blueprint $table) {
            $table->dropForeign('waterdetails_ibfk_1');
            $table->dropForeign('waterdetails_ibfk_2');
        });
    }
};
