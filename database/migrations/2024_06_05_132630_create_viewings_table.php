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
        Schema::create('viewings', function (Blueprint $table) {
            $table->integer('viewingNo', true);
            $table->string('houseNo')->nullable()->index('houseno');
            $table->integer('rentalNo')->nullable()->index('rentalno');
            $table->string('names', 300)->nullable();
            $table->integer('phoneNo')->nullable();
            $table->date('dateViewed')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viewings');
    }
};
