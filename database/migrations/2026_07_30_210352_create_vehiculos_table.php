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
            Schema::create('vehiculos', function (Blueprint $table) {

                $table->id();

                $table->string('placa',20)->unique();

                $table->year('anio');

                $table->string('color',30);

                $table->integer('kilometraje');

                $table->foreignId('cliente_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->timestamps();

            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
