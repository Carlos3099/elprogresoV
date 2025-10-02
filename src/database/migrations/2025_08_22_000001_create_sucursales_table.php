<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sucursales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('logo_path')->nullable();   // 👈 agregar
            $table->string('qr_path')->nullable();     // 👈 agregar
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('sucursales'); }
};
