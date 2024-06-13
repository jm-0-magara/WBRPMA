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
        Schema::create('expenditures', function (Blueprint $table) {
            $table->integer('expenditureID')->primary();
            $table->string('expenditureType')->nullable()->index('expendituretype');
            $table->integer('userID')->nullable()->index('userid');
            $table->integer('amount')->nullable();
            $table->date('timeRecorded')->nullable()->useCurrent();
            $table->date('timePaid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenditures');
    }
};
