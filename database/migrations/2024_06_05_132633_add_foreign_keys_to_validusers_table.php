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
        Schema::table('validusers', function (Blueprint $table) {
            $table->foreign(['userNo'], 'validusers_ibfk_1')->references(['userNo'])->on('appusers')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['rentalNo'], 'validusers_ibfk_2')->references(['rentalNo'])->on('rentals')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('validusers', function (Blueprint $table) {
            $table->dropForeign('validusers_ibfk_1');
            $table->dropForeign('validusers_ibfk_2');
        });
    }
};
