<?php

namespace App\Utils;

use Form;
use Illuminate\Support\Facades\Gate;

class JSONForms
{
    public static function generateForm($fila, $chamado = null)
    {
        // $template_default = [
        //     'descricao' => [
        //         'label' => 'Descrição',
        //         'type' => 'textarea',
        //     ],
        //     'notes' => [
        //         'label' => 'Anotações do atendente (oculto para o usuário)',
        //         'type' => 'textarea',
        //         'can' => 'admin',
        //     ],
        // ];
        // if ($fila->template) {
        //     $template = json_decode(json_encode(
        //         array_merge(json_decode($fila->template, true), $template_default)
        //     ));
        // } else {
        //     $template = json_decode(json_encode(
        //         $template_default
        //     ));
        // }
        $template = json_decode($fila->template);

        $form = [];
        $data = null;
        if ($template) {
            if ($chamado) {
                $data = json_decode($chamado->extras);
            }
            foreach ($template as $key => $json) {
                $type = $json->type;
                # se o template tem autorização
                if (isset($json->can)) {
                    if (!Gate::allows($json->can)) {
                        continue;
                    }
                }
                $value = null;
                $form[] = Form::label("extras[$key]", $template->$key->label, ['class' => 'control-label']);
                if (isset($data->$key)) {
                    $value = $data->$key;
                }
                $form[] = Form::$type("extras[$key]", $value, ['class' => 'form-control', 'rows' => '3']);
            }
        }
        return $form;
    }
}
