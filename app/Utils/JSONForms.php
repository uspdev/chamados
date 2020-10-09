<?php

namespace App\Utils;

use Collective\Html\FormFacade;
use Illuminate\Support\Facades\Gate;

class JSONForms
{
    public static function generateForm($fila, $chamado = null)
    {
        $template = json_decode($fila->template);
        $form = [];
        $data = null;
        if ($template) {
            if ($chamado) {
                $data = json_decode($chamado->extras);
            }
            foreach ($template as $key => $json) {
                $type = $json->type;
                $admin = $json->admin;
                # campo de admin precisa de permissão de admin
                if (!Gate::allows('admin') and $admin == "true") {
                    continue;
                }
                $value = null;
                $form[] = FormFacade::label("extras[$key]", $template->$key->label, ['class' => 'control-label']);
                if (isset($data->$key)) {
                    $value = $data->$key;
                }
                $form[] = FormFacade::$type("extras[$key]", $value, ['class' => 'form-control']);
            }
        } 
        return $form;
    }
}
