<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gastos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sucursal_id')->constrained('sucursales')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('fecha');
            $table->enum('categoria', ['servicios','renta','insumos','nomina','mantenimiento','otros']);
            $table->string('descripcion');
            $table->decimal('monto', 10, 2);
            $table->enum('metodo_pago', ['efectivo','transferencia','tarjeta']);
            $table->string('comprobante_url')->nullable();
            $table->timestamps();

            $table->index(['fecha','sucursal_id','categoria']);
        });
    }
    public function down(): void { Schema::dropIfExists('gastos'); }
};
