<?php

namespace Tests\Unit;

use App\Models\Chamado;
use App\Models\Fila;
use App\Models\Setor;
use App\Models\User;
use App\Utils\JSONForms;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class JSONFormsTest extends TestCase
{
    private $template = [
        "predio" => [
            "label" => "Prédio",
            "type" => "text"
        ]
    ];

    public function testTemplateFromFila()
    {
        Setor::factory()->create();
        $template = json_encode($this->template);
        $fila = Fila::factory()->make(['template' => $template]);
        $json = json_decode($fila->template);
        $this->assertEquals($json->predio->label, "Prédio");
    }

    public function testTemplateFromChamado()
    {
        $template = json_encode($this->template);
        # precisamos do fila_id para usar o acessor do chamado
        $fila = Fila::factory()->create(['template' => $template]);
        $chamado = Chamado::factory()->make([
            'fila_id' => $fila->id,
            'extras' => '{"predio": "Administração"}'
        ]);
        $key = key(json_decode($chamado->fila->template, true));
        $json = json_decode($chamado->extras);
        $this->assertEquals($json->$key, 'Administração');
    }

    public function testGenerateFormNoTemplate()
    {
        $fila = Fila::factory()->make();
        $this->assertEquals(JSONForms::generateForm($fila), []);
    }

    public function testGenerateFormTemplate()
    {
        $template = json_encode($this->template);
        $fila = Fila::factory()->make(['template' => $template]);
        $form = JSONForms::generateForm($fila);
        # JSONForms devolve um array de elementos
        #   um elemento é um label + input + help
        $this->assertStringContainsString("extras[predio]", $form[0][1]->toHtml());
    }

    public function testGenerateFormChamado()
    {
        $template = json_encode($this->template);
        $fila = Fila::factory()->create(['template' => $template]);
        $chamado = Chamado::factory()->make([
            'fila_id' => $fila->id,
            'extras' => '{"predio": "Administração"}'
        ]);
        $form = JSONForms::generateForm($fila, $chamado);
        $this->assertStringContainsString("Administração", $form[0][1]->toHtml());
    }

    public function testGenerateFormCannot()
    {
        $tmp = $this->template;
        $tmp["predio"]["can"] = "admin";
        $template = json_encode($tmp);
        $fila = Fila::factory()->make(['template' => $template]);
        $form = JSONForms::generateForm($fila);
        $this->assertEquals(JSONForms::generateForm($fila), []);
    }

    public function testGenerateFormCan()
    {
        $tmp = $this->template;
        $tmp["predio"]["can"] = "admin";
        $template = json_encode($tmp);
        $user = User::factory()->make(['is_admin' => 1]);
        $fila = Fila::factory()->make(['template' => $template]);
        $this->actingAs($user);
        session(['is_admin' => 1]);
        $form = JSONForms::generateForm($fila);
        $this->assertStringContainsString("extras[predio]", $form[0][1]->toHtml());
    }

    public function testGenerateFormSelect()
    {
        $tmp = $this->template;
        $tmp["predio"]["type"] = "select";
        $tmp["predio"]["value"] = [
            "1" => "Bloco A",
            "2" => "Administração"
        ];
        $template = json_encode($tmp);
        $fila = Fila::factory()->make(['template' => $template]);
        $form = JSONForms::generateForm($fila);
        $this->assertStringContainsString("Bloco A", $form[0][1]->toHtml());
    }

    public function testGenerateFormSelected()
    {
        $tmp = $this->template;
        $tmp["predio"]["type"] = "select";
        $tmp["predio"]["value"] = [
            "1" => "Bloco A",
            "2" => "Administração"
        ];
        $template = json_encode($tmp);
        $fila = Fila::factory()->create(['template' => $template]);
        $chamado = Chamado::factory()->make([
            'fila_id' => $fila->id,
            'extras' => '{"predio": 2}'
        ]);
        $form = JSONForms::generateForm($fila, $chamado);
        $this->assertStringContainsString('selected">Admin', $form[0][1]->toHtml());
    }

    public function testGenerateFormHelp()
    {
        $tmp = $this->template;
        $tmp["predio"]["help"] = "Local de atendimento";
        $template = json_encode($tmp);
        $fila = Fila::factory()->make(['template' => $template]);
        $form = JSONForms::generateForm($fila);
        # $form terá 3 elementos
        $this->assertStringContainsString("Local de atendimento", $form[0][2]->toHtml());
    }

    public function testBuildRules()
    {
        $tmp = $this->template;
        $tmp["predio"]["validate"] = "required";
        $template = json_encode($tmp);
        $fila = Fila::factory()->make(['template' => $template]);
        $request = new Request([], ["extras" => ["predio" => "required"]]);
        $validate = JSONForms::buildRules($request, $fila);
        $this->assertEquals($validate, ["extras.predio" => "required"]);
    }
}
