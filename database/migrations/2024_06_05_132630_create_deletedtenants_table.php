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
        Schema::create('deletedtenants', function (Blueprint $table) {
            $table->integer('deletedTenantNo')->primary();
            $table->string('houseNo')->nullable()->index('houseno');
            $table->integer('rentalNo')->nullable()->index('rentalno');
            $table->string('names')->nullable();
            $table->integer('phoneNo')->nullable();
            $table->integer('debt')->nullable();
            $table->date('dateDeleted')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deletedtenants');
    }
};
