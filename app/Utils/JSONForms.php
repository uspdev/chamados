<?php

namespace App\Utils;

/* use Form; */
use Illuminate\Support\HtmlString;

class JSONForms
{
    /**
     * Valida os campos do formulário
     *
     * @param $request Campos do formulário a serem validados
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
                    $field = 'extras.' . $key;
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
            $label = $template->$key->label;
            if (isset($json->validate) && strpos($json->validate, 'required') !== false) {
                $label = '<i class="fas fa-star-of-life fa-sm text-danger"></i> ' . $label;
            }
            $input[] = html()->label($label)->for("extras[$key]")->attribute('class','control-label');

            # valores preenchidos
            # aqui temos de usar "or" pois "||" não preenche corretamente
            $value = $data->$key ?? null;

            switch ($type) {
                //caso seja um select passa o valor padrao
                case 'select':
                    $fieldInput = html()->$type("extras[$key]", json_decode(json_encode($json->value), true))
                        ->class('form-control')->placeholder('Selecione...');;

                    if (isset($json->validate) && strpos($json->validate, 'required') !== false) {
                        $fieldInput  = $fieldInput ->required();
                    }

                    $input[] = $fieldInput;
                    break;

                default:
                    $fieldInput = html()->textarea("extras[$key]", $value)->class('form-control')->rows(3);

                    if (isset($json->validate) && strpos($json->validate, 'required') !== false) {
                        $fieldInput  = $fieldInput ->required();
                    }

                    $input[] = $fieldInput;
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
            $data = $chamado ? json_decode($chamado->extras) : null;
            $form = JSONForms::JSON2Form($template, $data, $perfil);
        }
        return $form;
    }
}
