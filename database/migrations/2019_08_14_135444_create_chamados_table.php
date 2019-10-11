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
            $table->timestamps();

            /* Campos obrigatórios*/
            $table->text('chamado');
            $predios = ['Administração', 'Letras','Filosofia e Ciências Sociais',
                         'História e Geografia','Casa de Cultura Japonesa','Favos','Outro'];
            $table->enum('predio', $predios);
            $table->text('sala');

            /* Campos opcionais do chamado */
            $table->text('patrimonio')->nullable();
            
            $table->enum('status', ['Triagem', 'Atribuído','Fechado']);
            $table->dateTime('atribuido_em')->nullable();
            $table->dateTime('fechado_em')->nullable();

            /* Campos da triagem */
            $table->enum('complexidade', ['baixa', 'média','alta'])->nullable();
            $table->integer('triagem_por')->unsigned()->nullable(); // codpes
            $table->integer('atribuido_para')->unsigned()->nullable(); // codpes

            // relacionamento com user
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            
            // relacionamento com categorias
            $table->unsignedBigInteger('categoria_id')->nullable();
            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('set null');
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
