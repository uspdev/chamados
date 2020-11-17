<?php

namespace Tests\Unit;

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

    private function noadmin403($method, $url)
    {
        $user = User::factory()->make();
        $this->actingAs($user);
        $response = $this->$method($url);
        $response->assertStatus(403);
    }

    private function admin200($method, $url)
    {
        $user = User::factory()->make(['is_admin' => 1]);
        $this->actingAs($user);
        session(['is_admin' => 1]);
        $response = $this->$method($url);
        $response->assertStatus(200);
        # o restante das checagens é feita em cada teste
        return $response;
    }

    #
    # index
    #
    public function testFilaIndexDeslogado()
    {
        $this->deslogado('get', '/filas');
    }

    public function testFilaIndexNoAdmin()
    {
        $this->noadmin403('get', '/filas');
    }

    public function testFilaIndexAdmin()
    {
        $response = $this->admin200('get', '/filas');
        $this->assertStringContainsString('Filas</span>', $response->content());
    }

    #
    # store
    #
    public function testFilaStoreDeslogado()
    {
        $this->deslogado('post', '/filas');
    }

    public function testFilaStoreNoAdmin()
    {
        $this->noadmin403('post', '/filas');
    }

    # precisa de bd
    # TODO public function testFilaStoreAdmin() {}

    #
    # create
    #
    public function testFilaCreateDeslogado()
    {
        $this->deslogado('get', '/filas/create');
    }

    public function testFilaCreateNoAdmin()
    {
        $this->noadmin403('/filas/create', 'get');
    }

    public function testFilaCreateAdmin()
    {
        $response = $this->admin200('get', '/filas/create');
        $this->assertStringContainsString('Criar nova fila</span>', $response->content());
    }

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
        $response = $this->admin200('get', '/filas/1');
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

    # precisa de bd
    # TODO public function testUpdateAdmin() {}

    #
    # destroy
    #
    public function testDestroyDeslogado()
    {
        $this->deslogado('delete', '/filas/1');
    }

    public function testDestroyNoAdmin()
    {
        $this->noadmin403('delete', '/filas/1');
    }

    # precisa de bd
    # TODO public function testDestroyAdmin() {}

    #
    # edit teoricamente está desativado
    #

    #
    # storePessoa
    #
    public function testStorePessoaDeslogado()
    {
        $this->deslogado('post', '/filas/1/pessoas');
    }

    public function testStorePessoaNoAdmin()
    {
        $this->noadmin403('post', '/filas/1/pessoas');
    }

    # precisa de bd
    # TODO public function testStorePessoaAdmin() {}

    #
    # destroyPessoa
    #
    public function testDestroyPessoaDeslogado()
    {
        $this->deslogado('delete', '/filas/1/pessoas/1');
    }

    public function testDestroyPessoaNoAdmin()
    {
        $this->noadmin403('delete', '/filas/1/pessoas/1');
    }

    # precisa de bd
    # TODO public function testDestroyPessoaAdmin() {}

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
        $response = $this->admin200('get', '/filas/1/template');
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

    # precisa de bd
    # TODO public function testStoreTemplateAdmin()
}
