<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modelos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('marca_id')
                ->constrained('marcas')
                ->restrictOnDelete();

            $table->string('nombre', 80);

            $table->timestamps();

            $table->unique([
                'marca_id',
                'nombre',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modelos');
    }
};