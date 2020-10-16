<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChamadosVinculadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chamados_vinculados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chamado_id')->constrained('chamados')->onDelete('cascade');
            $table->foreignId('vinculado_id')->constrained('chamados')->onDelete('cascade');

            # talvez o acesso deveria ser somente leitura se não vai bugar
            # para descobrir se o usuario tem acesso ou não a determinado chamado
            # teria de verificar em todos os relacionados
            $table->enum('acesso', ['leitura','completo']);
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
        Schema::dropIfExists('user_chamado');
    }
}
