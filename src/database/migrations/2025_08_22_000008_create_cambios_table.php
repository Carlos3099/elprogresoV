<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cambios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_original_id')->nullable()->constrained('ventas')->nullOnDelete();
            $table->foreignId('sucursal_id')->constrained('sucursales')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->dateTime('fecha');
            $table->decimal('total_devuelto', 10, 2)->default(0);
            $table->decimal('total_entregado', 10, 2)->default(0);
            $table->decimal('diferencia', 10, 2)->default(0);
            $table->enum('metodo_ajuste', ['efectivo','transferencia','tarjeta','nota_credito'])->nullable();
            $table->string('referencia_pago')->nullable();
            $table->string('notas')->nullable();
            $table->timestamps();

            $table->index(['fecha', 'sucursal_id', 'usuario_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('cambios'); }
};
