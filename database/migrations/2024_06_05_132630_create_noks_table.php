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
        Schema::create('noks', function (Blueprint $table) {
            $table->integer('nextOfKinNo', true);
            $table->string('nextOfKinName')->nullable();
            $table->integer('nextOfKinPhoneNo')->nullable();
            $table->integer('tenantNo')->nullable()->index('tenantno');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noks');
    }
};
