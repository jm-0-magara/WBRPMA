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
        Schema::create('payments', function (Blueprint $table) {
            $table->integer('paymentID')->primary();
            $table->string('houseNo')->nullable()->index('houseno');
            $table->integer('rentalNo')->nullable()->index('rentalno');
            $table->string('paymentType')->nullable()->index('paymenttype');
            $table->integer('amount')->nullable();
            $table->date('timeRecorded')->nullable()->useCurrent();
            $table->date('timePaid')->nullable();
            $table->string('paymentMethod')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
