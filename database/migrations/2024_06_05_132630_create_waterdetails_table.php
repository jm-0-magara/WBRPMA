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
        Schema::create('waterdetails', function (Blueprint $table) {
            $table->integer('waterConsumedID')->primary();
            $table->string('houseNo')->nullable()->index('houseno');
            $table->integer('rentalNo')->nullable()->index('rentalno');
            $table->integer('unitsConsumed')->nullable();
            $table->date('date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waterdetails');
    }
};
