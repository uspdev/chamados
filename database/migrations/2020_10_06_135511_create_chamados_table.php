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
            $table->integer('nro');
            $table->string('assunto', 255);
            $table->string('status', 30);

            /* Campos opcionais do chamado */
            $table->text('descricao')->nullable();
            $table->dateTime('fechado_em')->nullable();
            $table->string('complexidade', 15)->nullable();
            $table->json('extras')->nullable();

            # visível somente para atendentes
            $table->text('anotacoes')->nullable();

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
