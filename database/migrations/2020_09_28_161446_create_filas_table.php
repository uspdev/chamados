<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filas', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 90);
            $table->string('estado', 90);
            $table->string('descricao', 255)->nullable();

            # modelo de formulário opcional da fila
            $table->json('template')->nullable();

            # configurações específicas
            $table->json('config')->nullable();

            # relacionamento com setor
            $table->foreignId('setor_id')->constrained('setores');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filas');
    }
}
