<?php

namespace App\Utils;

use Form;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\HtmlString;

class JSONForms
{
    public static function buildRules($request, $fila)
    {
        $template = json_decode($fila->template);
            $validate = [];
            if ($template) {
            foreach ($template as $key => $json) {
                if (isset($json->validate)) {
                    $field = "extras.".$key;
                    $validate[$field] = $json->validate;
                }
            }
        }
        return $validate;
    }

    public static function JSON2Form($template, $data)
    {
        Form::macro('help', function($text)
        {
            $help = new HtmlString('<small class="form-text text-muted">'.$text.'</small>');
            return $help;
        });

        $form = [];
        foreach ($template as $key => $json) {
            $input = [];
            $type = $json->type;

            # se o template tem autorização
            if (isset($json->can)) {
                if (!Gate::allows($json->can)) {
                    continue;
                }
            }

            $input[] = Form::label("extras[$key]", $template->$key->label, ['class' => 'control-label']);

            # valores preenchidos
            $value = null;
            if (isset($data->$key)) {
                $value = $data->$key;
            }

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
                $input[] = Form::help($json->help);
            }
            $form[] = $input;
        }
        return $form;
    }

    public static function generateForm($fila, $chamado = null)
    {
        $template = json_decode($fila->template);
        $data = null;
        $form = [];
        if ($template) {
            if ($chamado) {
                $data = json_decode($chamado->extras);
            }
            $form = JSONForms::JSON2Form($template, $data);
        }
        return $form;
    }
}
