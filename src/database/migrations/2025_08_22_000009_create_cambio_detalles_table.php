<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cambio_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cambio_id')->constrained('cambios')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->restrictOnDelete();
            $table->enum('tipo', ['devuelto','entregado']);
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('total_linea', 10, 2);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('cambio_detalles'); }
};
