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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained()->onDelete('cascade');
            $table->enum('metodo_pago', ['efectivo', 'transferencia', 'tarjeta','multipago']);
            $table->decimal('monto', 10, 2);
            $table->string('referencia_pago', 255)->nullable();
            $table->enum('estado', ['pendiente', 'completado', 'fallido'])->default('completado');
            $table->timestamp('fecha_pago')->nullable();
            $table->enum('destinatario_transferencia', ['Karen', 'Ethan'])->nullable(); // ← Agregar este campo
            $table->timestamps();
            
            // Índices para mejor performance
            $table->index('venta_id');
            $table->index('metodo_pago');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};