<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChamadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chamados', function (Blueprint $table) {
            $table->bigIncrements('id');

            /* Campos obrigatórios*/
            $table->text('assunto');

            /* Campos opcionais do chamado */
            $table->enum('status', ['Triagem', 'Atribuído','Fechado']);
            $table->dateTime('atribuido_em')->nullable();
            $table->dateTime('fechado_em')->nullable();

            /* Campos da triagem */
            $table->enum('complexidade', ['baixa', 'média','alta'])->nullable();
            $table->integer('triagem_por')->unsigned()->nullable(); // codpes
            $table->integer('atribuido_para')->unsigned()->nullable(); // codpes
            $table->json('extras')->nullable();

            /* Relacionamentos */
            $table->foreignId('fila_id')->constrained('filas');

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
        Schema::dropIfExists('chamados');
    }
}
