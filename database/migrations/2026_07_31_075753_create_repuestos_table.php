<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repuestos', function (Blueprint $table) {
            $table->id();

            $table->string('codigo', 50)->unique();

            $table->string('nombre', 150);

            $table->text('descripcion')->nullable();

            $table->decimal('precio_compra', 10, 2)
                ->default(0);

            $table->decimal('precio_venta', 10, 2);

            $table->unsignedInteger('stock')
                ->default(0);

            $table->unsignedInteger('stock_minimo')
                ->default(0);

            $table->string('estado', 20)
                ->default('Activo');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repuestos');
    }
};