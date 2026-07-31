<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordenes_trabajo', function (Blueprint $table) {
            $table->id();

            $table->string('codigo', 20)
                ->nullable()
                ->unique();

            $table->foreignId('vehiculo_id')
                ->constrained('vehiculos')
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->date('fecha_ingreso');

            $table->date('fecha_salida_estimada')
                ->nullable();

            $table->string('estado', 30)
                ->default('Pendiente');

            $table->text('diagnostico')
                ->nullable();

            $table->text('observaciones')
                ->nullable();

            $table->decimal('total', 10, 2)
                ->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordenes_trabajo');
    }
};