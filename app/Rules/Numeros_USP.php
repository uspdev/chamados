<?php
namespace App\Rules;
use Illuminate\Contracts\Validation\Rule;
use Respect\Validation\Validator as v;
use Uspdev\Replicado\Pessoa;

class Numeros_USP implements Rule
{
    private $field;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($field = null)
    {
        $this->field = $field;
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
        if (!(is_numeric(trim($value)))) {
            return false;
        }
        if(config('sites.usar_replicado') == true){
            if(empty(Pessoa::dump($value))) {
                return false;
            }
        }
        return true;
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Número USP não válido';
    }
}
