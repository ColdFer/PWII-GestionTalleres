<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_orden', function (Blueprint $table) {
            $table->id();

            $table->foreignId('orden_trabajo_id')
                ->constrained('ordenes_trabajo')
                ->cascadeOnDelete();

            $table->foreignId('servicio_id')
                ->constrained('servicios')
                ->restrictOnDelete();

            $table->decimal('precio', 10, 2);

            $table->timestamps();

            $table->unique([
                'orden_trabajo_id',
                'servicio_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_orden');
    }
};