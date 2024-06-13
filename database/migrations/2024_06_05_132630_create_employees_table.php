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
        Schema::create('employees', function (Blueprint $table) {
            $table->integer('employeeNo', true);
            $table->string('employeeRole')->nullable()->index('employeerole');
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->integer('phoneNo')->nullable();
            $table->integer('salary')->nullable();
            $table->integer('img')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
