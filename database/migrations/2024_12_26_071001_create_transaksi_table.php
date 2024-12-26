<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('uid', 50);
            $table->integer('tarif');
            $table->integer('saldo_akhir');
            $table->timestamp('waktu_transaksi');
            $table->timestamps();  // Menambahkan kolom created_at dan updated_at
            $table->foreign('uid')->references('uid')->on('users');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
