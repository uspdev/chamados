<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Uspdev\Replicado\Bempatrimoniado;

class PatrimonioRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $wrong;

    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (empty(trim($value))) {
            return true;
        }
                    
        $values = explode(',',$value);
        #if(config('atendimento.usar_replicado') == true) {
            foreach($values as $v) { 
                if(!Bempatrimoniado::verifica($v)) {
                    $this->wrong = $v;
                    return false;
                }
            }
        #}
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Patrimônio ' . $this->wrong .' não é válido';
    }
}
