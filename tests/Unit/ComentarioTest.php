<?php

namespace Tests\Unit;

use App\Models\Chamado;
use App\Models\Comentario;
use App\Models\Fila;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class ComentarioTest extends TestCase
{
    private function comentario(array $filaConfig = [], array $comentarioConfig = [])
    {
        $createdAt = $comentarioConfig['created_at'] ?? Carbon::now();
        $updatedAt = $comentarioConfig['updated_at'] ?? $createdAt;
        unset($comentarioConfig['created_at']);
        unset($comentarioConfig['updated_at']);

        $config = array_merge([
            'triagem' => 0,
            'patrimonio' => 0,
            'editar_comentarios' => 1,
            'visibilidade' => config('filas.config.visibilidade'),
            'status' => [],
        ], $filaConfig);

        $fila = new Fila();
        $fila->setRawAttributes(['config' => json_encode($config)], true);

        $chamado = new Chamado();
        $chamado->setRelation('fila', $fila);

        $comentario = new Comentario(array_merge([
            'user_id' => 1,
            'tipo' => 'user',
            'comentario' => 'Comentário',
        ], $comentarioConfig));
        $comentario->created_at = $createdAt;
        $comentario->updated_at = $updatedAt;
        $comentario->setRelation('chamado', $chamado);

        return $comentario;
    }

    public function testPodeEditarComentarioProprioDentroDoTimeoutQuandoFilaPermite()
    {
        $user = new User();
        $user->id = 1;
        $comentario = $this->comentario();

        $this->assertTrue($comentario->podeSerEditadoPor($user));
    }

    public function testNaoPodeEditarQuandoFilaNaoPermite()
    {
        $user = new User();
        $user->id = 1;
        $comentario = $this->comentario(['editar_comentarios' => 0]);

        $this->assertFalse($comentario->podeSerEditadoPor($user));
    }

    public function testNaoPodeEditarComentarioDeOutroUsuario()
    {
        $user = new User();
        $user->id = 2;
        $comentario = $this->comentario();

        $this->assertFalse($comentario->podeSerEditadoPor($user));
    }

    public function testNaoPodeEditarDepoisDoTimeout()
    {
        $user = new User();
        $user->id = 1;
        $comentario = $this->comentario([], [
            'created_at' => Carbon::now()->subHours(config('chamados.editar_comentarios_timeout_horas') + 1),
        ]);

        $this->assertFalse($comentario->podeSerEditadoPor($user));
    }

    public function testTimeoutDaFilaSobrescreveTimeoutGlobal()
    {
        config(['chamados.editar_comentarios_timeout_horas' => 12]);

        $user = new User();
        $user->id = 1;
        $comentario = $this->comentario([
            'editar_comentarios_timeout_horas' => 24,
        ], [
            'created_at' => Carbon::now()->subHours(config('chamados.editar_comentarios_timeout_horas') + 1),
        ]);

        $this->assertTrue($comentario->podeSerEditadoPor($user));
    }

    public function testTimeoutZeroPermiteEditarSempre()
    {
        $user = new User();
        $user->id = 1;
        $comentario = $this->comentario([
            'editar_comentarios_timeout_horas' => 0,
        ], [
            'created_at' => Carbon::now()->subYear(),
        ]);

        $this->assertTrue($comentario->podeSerEditadoPor($user));
    }

    public function testComentarioNovoNaoFicaSinalizadoComoEditado()
    {
        $data = Carbon::now();
        $comentario = $this->comentario([], [
            'created_at' => $data,
            'updated_at' => $data,
        ]);

        $this->assertFalse($comentario->foiEditado());
    }

    public function testComentarioEditadoFicaSinalizadoComoEditado()
    {
        $comentario = $this->comentario([], [
            'created_at' => Carbon::now()->subMinute(),
            'updated_at' => Carbon::now(),
        ]);

        $this->assertTrue($comentario->foiEditado());
    }
}
