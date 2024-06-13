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
        Schema::table('expenditures', function (Blueprint $table) {
            $table->foreign(['expenditureType'], 'expenditures_ibfk_1')->references(['expenditureType'])->on('expendituretypes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['userID'], 'expenditures_ibfk_2')->references(['userID'])->on('validusers')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenditures', function (Blueprint $table) {
            $table->dropForeign('expenditures_ibfk_1');
            $table->dropForeign('expenditures_ibfk_2');
        });
    }
};
