<?php

namespace Tests\Unit;

use App\Models\Fila;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FilaAuthTest extends TestCase
{
    # auxiliares
    private function deslogado($method, $url)
    {
        $response = $this->$method($url);
        $response->assertStatus(302);
        $this->assertStringContainsString('/login</title>', $response->content());
    }

    private function noadmin403($method, $url, $data = [])
    {
        $user = User::factory()->make();
        $this->actingAs($user);
        if ($method == 'post') {
            $response = $this->$method($url, $data);
        }
        else {
            $response = $this->$method($url);
        }
        $response->assertStatus(403);
    }

    private function adminSuccess($method, $url, $data = [], $code = 200)
    {
        $user = User::factory()->make(['is_admin' => 1]);
        $this->actingAs($user);
        session(['is_admin' => 1]);
        if ($method == 'post') {
            $response = $this->$method($url, $data);
        }
        else {
            $response = $this->$method($url);
        }
        $response->assertStatus($code);
        # o restante das checagens é feita em cada teste
        return $response;
    }

    #
    # index
    #
    public function testIndexDeslogado()
    {
        $this->deslogado('get', '/filas');
    }

    public function testIndexNoAdmin()
    {
        $this->noadmin403('get', '/filas');
    }

    public function testIndexAdmin()
    {
        $response = $this->adminSuccess('get', '/filas');
        $this->assertStringContainsString('Filas</span>', $response->content());
    }

    #
    # store
    #
    public function testStoreDeslogado()
    {
        $this->deslogado('post', '/filas');
    }

    public function testStoreNoAdmin()
    {
        $data = [
            'setor_id' => 1,
            'nome' => 'teste',
            'descricao' => 'teste'
        ];
        $this->noadmin403('post', '/filas', $data);
    }

    public function testStoreAdmin()
    {
        $data = [
            'setor_id' => 1,
            'nome' => 'teste',
            'descricao' => 'teste'
        ];
        $response = $this->adminSuccess('post', '/filas', $data, 302);
        $fila = Fila::where($data)->first();
        $fila->delete();
        $this->assertEquals(Fila::where($data)->first(), null);
    }

    #
    # create desativado
    #

    #
    # show
    #
    public function testShowDeslogado()
    {
        $this->deslogado('get', '/filas/1');
    }

    public function testShowNoAdmin()
    {
        $this->noadmin403('get', '/filas/1');
    }

    public function testShowAdmin()
    {
        $response = $this->adminSuccess('get', '/filas/1');
        $this->assertStringContainsString('Setor:', $response->content());
    }

    #
    # update
    #
    public function testUpdateDeslogado()
    {
        $this->deslogado('put', '/filas/1');
    }

    public function testUpdateNoAdmin()
    {
        $this->noadmin403('put', '/filas/1');
    }

    public function testUpdateAdmin()
    {
        $data = [
            '_method' => 'PUT',
            'nome' => 'teste2'
        ];
        $response = $this->adminSuccess('post', '/filas/1', $data, 302);
        $fila = Fila::where('id', 1)->first();
        $this->assertEquals($fila->nome, 'teste2');
    }

    #
    # destroy desativado
    #

    #
    # edit desativado
    #

    #
    # storePessoa
    #
    public function testStorePessoaDeslogado()
    {
        $this->deslogado('post', '/filas/1/pessoas');
    }

    # user_fila não tem funcao padrão
    public function testStorePessoaNoAdmin()
    {
        $data = [
            'codpes' => '3141592',
            'funcao' => 'Gerente'
        ];
        $this->noadmin403('post', '/filas/1/pessoas', $data);
    }

    public function testStorePessoaAdmin()
    {
        $codpes = User::first()->codpes;
        $data = [
            'codpes' => $codpes,
            'funcao' => 'Gerente'
        ];
        $response = $this->adminSuccess('post', '/filas/1/pessoas', $data, 302);
        $fila = Fila::where('id', 1)->first();
        $user = $fila->users->where('codpes', $codpes)->first();
        $this->assertEquals($user->pivot->funcao, 'Gerente');
    }

    #
    # destroyPessoa
    #
    public function testDestroyPessoaDeslogado()
    {
        $this->deslogado('delete', '/filas/1/pessoas/1');
    }

    /* aqui não estou usando que o Fila::first() é a fila com id 1 */
    public function testDestroyPessoaNoAdmin()
    {
        $id = Fila::first()->id;
        $user = Fila::first()->users[0]->id;
        $this->noadmin403('delete', "/filas/$id/pessoas/$user");
    }

    /* aqui não estou usando que o Fila::first() é a fila com id 1 */
    public function testDestroyPessoaAdmin()
    {
        $fila = Fila::first();
        $user = User::first();
        $fila->users()->attach($user->id, ['funcao' => 'Gerente']);
        $data = [
            '_method' => 'delete'
        ];
        $response = $this->adminSuccess('post', "/filas/$fila->id/pessoas/$user->id", $data, 302);
        $fila->refresh();
        $this->assertEquals($fila->users->where('id', $user->id)->first(), null);
    }

    #
    # createTemplate
    #
    public function testCreateTemplateDeslogado()
    {
        $this->deslogado('get', '/filas/1/template');
    }

    public function testCreateTemplateNoAdmin()
    {
        $this->noadmin403('get', '/filas/1/template');
    }

    public function testCreateTemplateAdmin()
    {
        $response = $this->adminSuccess('get', '/filas/1/template');
        $this->assertStringContainsString('Criar template para a fila:', $response->content());
    }

    #
    # storeTemplate
    #
    public function testStoreTemplateDeslogado()
    {
        $this->deslogado('post', '/filas/1/template');
    }

    public function testStoreTemplateNoAdmin()
    {
        $this->noadmin403('post', '/filas/1/template');
    }

    public function testStoreTemplateAdmin()
    {
        $new = [
            'label' => 'teste',
            'type' => 'text'
        ];
        $data = [
            'campo' => 'teste',
            'new' => $new
        ];
        $response = $this->adminSuccess('post', '/filas/1/template', $data, 302);
        $fila = Fila::first();
        $this->assertStringContainsString('"label":"teste"', $fila->template);
    }

    #
    # storeTemplateJson
    #

    public function testStoreTemplateJsonDeslogado()
    {
        $this->deslogado('post', '/filas/1/template_json');
    }

    public function testStoreTemplateJsonNoAdmin()
    {
        $this->noadmin403('post', '/filas/1/template_json');
    }

    public function testStoreTemplateJsonAdmin()
    {
        $data = [
            'template' => '{"teste2":{"label":"teste2","type":"text"}}'
        ];
        $response = $this->adminSuccess('post', '/filas/1/template_json', $data, 302);
        $fila = Fila::first();
        $this->assertStringContainsString('"label":"teste2"', $fila->template);
    }
}
