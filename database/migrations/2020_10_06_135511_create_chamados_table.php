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
            $table->text('chamado');
            $predios = [
                'Administração', 'Letras',
                'Filosofia e Ciências Sociais',
                'História e Geografia',
                'Casa de Cultura Japonesa',
                'Favos', 'Outro'
            ];
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

            /* Relacionamentos */
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('fila_id')->nullable()->constrained('filas')->onDelete('set null');

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
