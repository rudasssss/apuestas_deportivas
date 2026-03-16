<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApuestasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apuestas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');

            $table->string('tipo_apuesta'); 
            $table->decimal('monto', 10, 2);
            $table->decimal('cuota', 4, 2);

            $table->string('estado')->default('pendiente');

            $table->decimal('ganancia', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apuestas');
    }
}
