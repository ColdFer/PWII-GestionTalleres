<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mecanicos', function (Blueprint $table) {
            $table->id();

            $table->string('nombre', 100);
            $table->string('apellido', 100);

            $table->string('ci', 30)
                ->nullable()
                ->unique();

            $table->string('telefono', 30)
                ->nullable();

            $table->string('email', 150)
                ->nullable()
                ->unique();

            $table->foreignId('especialidad_id')
                ->constrained('especialidades')
                ->restrictOnDelete();

            $table->string('estado', 20)
                ->default('Activo');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mecanicos');
    }
};