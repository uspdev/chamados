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
            'assunto' => 'required',
            'descricao' => 'required',
            'extras' => 'nullable',
            'complexidade' => 'nullable',
        ];
        return $rules;
    }

    protected function prepareForValidation()
    {
    }

    public function messages()
    {
        return [
            'assunto.required' => 'Preencher o campo assunto',
            'descricao.required' => 'Preencher o campo descrição',
        ];
    }
}
