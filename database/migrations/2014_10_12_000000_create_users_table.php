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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
<<<<<<< HEAD
            $table->string('uid',50)->unique();
            $table->string('nama',100);
            $table->integer('saldo');
=======
            $table->string('uid', 50)->unique();
            $table->string('nama', 100);
            $table->integer('saldo', );
>>>>>>> 92dede23f7465d5cdf94469304be9379de1ab580
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
