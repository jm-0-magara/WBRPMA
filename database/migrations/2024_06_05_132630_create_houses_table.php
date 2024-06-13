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
        Schema::create('houses', function (Blueprint $table) {
            $table->string('houseNo');
            $table->integer('rentalNo')->index('rentalno');
            $table->string('structureName')->nullable()->index('structurename');
            $table->string('houseType')->nullable()->index('housetype');
            $table->string('status')->nullable();
            $table->boolean('isPaid')->nullable();

            $table->primary(['houseNo', 'rentalNo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('houses');
    }
};
