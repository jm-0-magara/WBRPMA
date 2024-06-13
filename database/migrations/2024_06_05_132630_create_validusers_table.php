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
        Schema::create('validusers', function (Blueprint $table) {
            $table->integer('userID', true);
            $table->integer('userNo')->nullable()->index('userno');
            $table->integer('rentalNo')->nullable()->index('rentalno');
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('password')->nullable();
            $table->string('location')->nullable();
            $table->integer('phoneNo')->nullable();
            $table->integer('interestPercentage')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validusers');
    }
};
