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
        Schema::table('noks', function (Blueprint $table) {
            $table->foreign(['tenantNo'], 'noks_ibfk_1')->references(['tenantNo'])->on('tenants')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('noks', function (Blueprint $table) {
            $table->dropForeign('noks_ibfk_1');
        });
    }
};
