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
        Schema::table('structures', function (Blueprint $table) {
            $table->foreign(['structureType'], 'structures_ibfk_1')->references(['structureType'])->on('structuretypes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['rentalNo'], 'structures_ibfk_2')->references(['rentalNo'])->on('rentals')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('structures', function (Blueprint $table) {
            $table->dropForeign('structures_ibfk_1');
            $table->dropForeign('structures_ibfk_2');
        });
    }
};
