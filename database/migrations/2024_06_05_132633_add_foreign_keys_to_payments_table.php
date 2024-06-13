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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreign(['houseNo'], 'payments_ibfk_1')->references(['houseNo'])->on('houses')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['rentalNo'], 'payments_ibfk_2')->references(['rentalNo'])->on('houses')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['paymentType'], 'payments_ibfk_3')->references(['paymentType'])->on('paymenttypes')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign('payments_ibfk_1');
            $table->dropForeign('payments_ibfk_2');
            $table->dropForeign('payments_ibfk_3');
        });
    }
};
