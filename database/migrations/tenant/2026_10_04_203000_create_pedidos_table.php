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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('codigo')->unique(); // Ej: PED-0001
            $table->string('tipo_entrega')->default('Envío a Domicilio');
            $table->text('direccion_entrega')->nullable();
            $table->string('metodo_pago')->default('Efectivo contra entrega');
            $table->string('estado')->default('pendiente'); // 'pendiente', 'en _proceso', 'completado', 'cancelado'
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->text('notas')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
