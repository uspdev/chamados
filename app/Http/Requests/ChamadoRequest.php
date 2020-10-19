<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChamadoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            #'telefone'       => 'required',
            'assunto'        => 'required',
            'descricao'      => 'required',
            'patrimonio'     => 'nullable',
            'extras'         => 'nullable',
            'codpes'         => 'nullable|codpes',
            'complexidade'   => 'nullable',
            'atribuido_para' => 'nullable'
        ];
        return $rules;
    }

    protected function prepareForValidation()
    {
    }

    public function messages()
    {
        return [
            #'telefone.required' => 'Telefone requerido',
            'descricao.required' => 'Preencher o campo descrição'
        ];
    }
}
