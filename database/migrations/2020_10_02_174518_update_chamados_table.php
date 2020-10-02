<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateChamadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (DB::connection()->getDriverName() == 'sqlite') {
            Schema::rename('chamados', 'chamadold');
            Schema::rename('comentarios', 'comentold');
            Schema::create('chamados', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->text('chamado');
                $predios = ['Administração', 'Letras','Filosofia e Ciências Sociais',
                         'História e Geografia','Casa de Cultura Japonesa','Favos','Outro'];
                $table->enum('predio', $predios);
                $table->text('sala');
                $table->text('patrimonio')->nullable();
                $table->enum('status', ['Triagem', 'Atribuído','Fechado']);
                $table->dateTime('atribuido_em')->nullable();
                $table->dateTime('fechado_em')->nullable();
                $table->enum('complexidade', ['baixa', 'média','alta'])->nullable();
                $table->integer('triagem_por')->unsigned()->nullable();
                $table->integer('atribuido_para')->unsigned()->nullable();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->foreignId('fila_id')->nullable()->constrained('filas')->onDelete('set null');
                $table->timestamps();
            });
            Schema::create('comentarios', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->text('comentario');
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->foreignId('chamado_id')->nullable()->constrained('chamados')->onDelete('set null');
            });
            DB::statement("INSERT INTO chamados SELECT * FROM chamadold");
            DB::statement("INSERT INTO comentarios SELECT * FROM comentold");
            Schema::drop('chamadold');
            Schema::drop('comentold');
        }
        else {
            Schema::table('chamados', function (Blueprint $table) {
                $table->foreignId('fila_id')->nullable()->constrained('filas')->onDelete('set null');
                $table->dropForeign(['categoria_id']);
            });
        }
        Schema::dropIfExists('categorias');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (DB::connection()->getDriverName() == 'sqlite') {
            Schema::rename('chamados', 'chamadold');
            Schema::rename('comentarios', 'comentold');
            Schema::create('chamados', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->text('chamado');
                $predios = ['Administração', 'Letras','Filosofia e Ciências Sociais',
                         'História e Geografia','Casa de Cultura Japonesa','Favos','Outro'];
                $table->enum('predio', $predios);
                $table->text('sala');
                $table->text('patrimonio')->nullable();
                $table->enum('status', ['Triagem', 'Atribuído','Fechado']);
                $table->dateTime('atribuido_em')->nullable();
                $table->dateTime('fechado_em')->nullable();
                $table->enum('complexidade', ['baixa', 'média','alta'])->nullable();
                $table->integer('triagem_por')->unsigned()->nullable();
                $table->integer('atribuido_para')->unsigned()->nullable();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->foreignId('categoria_id')->nullable()->constrained('categorias')->onDelete('set null');
                $table->timestamps();
            });
            Schema::create('comentarios', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->text('comentario');
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->foreignId('chamado_id')->nullable()->constrained('chamados')->onDelete('set null');
            });
            DB::statement("INSERT INTO chamados SELECT * FROM chamadold");
            DB::statement("INSERT INTO comentarios SELECT * FROM comentold");
            Schema::drop('chamadold');
            Schema::drop('comentold');
        }
        else {
            Schema::table('chamados', function (Blueprint $table) {
                $table->foreignId('categoria_id')->nullable()->constrained('categorias')->onDelete('set null');
                $table->dropForeign(['fila_id']);
            });
        }
        Schema::create('categorias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->text('nome');
            // isso muda no SQLite, já que o constraint não existia lá
            $table->foreignId('setor_id')->nullable()->constrained('setores');
        });
    }
}
