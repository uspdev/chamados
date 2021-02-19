<?php

namespace App\Utils;

use Form;
use Illuminate\Support\HtmlString;

class JSONForms
{
    /**
     * Valida os campos do formulário
     *
     * @param $request Campos do do formulário a serem validados
     * @param $fila Fila de onde vai pegar as regras de validação
     *
     * @return Array Contendo a validação
     */
    public static function buildRules($request, $fila)
    {
        $template = json_decode($fila->template);
        $validate = [];
        if ($template) {
            foreach ($template as $key => $json) {
                if (isset($json->validate)) {
                    $field = "extras." . $key;
                    $validate[$field] = $json->validate;
                }
            }
        }
        return $validate;
    }

    /**
     * Renderiza o formulário como array contendo html
     */
    protected static function JSON2Form($template, $data, $perfil)
    {
        $form = [];
        foreach ($template as $key => $json) {
            $input = [];
            $type = $json->type;

            $input[] = Form::label("extras[$key]", $template->$key->label, ['class' => 'control-label']);

            # valores preenchidos
            # aqui temos de usar "or" pois "||" não preenche corretamente
            $value = $data->$key ?? null;

            switch ($type) {
                //caso seja um select passa o valor padrao
                case 'select':
                    $input[] = Form::$type("extras[$key]", $json->value, $value, ['class' => 'form-control', 'placeholder' => 'Selecione...']);
                    break;

                default:
                    $input[] = Form::$type("extras[$key]", $value, ['class' => 'form-control', 'rows' => '3']);
                    break;
            }

            if (isset($json->help)) {
                $input[] = new HtmlString('<small class="form-text text-muted">' . $json->help . '</small>');
            }

            # vamos incluir o input se "can for igual ao perfil" ou "se não houver can"
            if (($perfil && isset($json->can) && $json->can == $perfil) || (!$perfil && !isset($json->can))) {
                $form[] = $input;
            }
        }
        return $form;
    }

    /**
     * Trata as entradas para renderizar o formulário
     */
    public static function generateForm($fila, $chamado = null, $perfil = null)
    {
        $template = json_decode($fila->template);
        $form = [];
        if ($template) {
            $data = json_decode($chamado->extras) ?? null;
            $form = JSONForms::JSON2Form($template, $data, $perfil);
        }
        return $form;
    }
}
