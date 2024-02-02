<?php

namespace App\Listeners;

use App\Models\Setor;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;
use Uspdev\Replicado\Pessoa;

class LoginListener
{
    public function __construct()
    {
    }

    public function handle(Login $event)
    {
        $user = $event->user;
        $vinculos = Pessoa::listarVinculosAtivos($user->codpes, false);
        $log = 'login listener:';

        # vincular a pessoa e o vinculo ao setor
        foreach ($vinculos as $vinculo) {
            if ($setor = Setor::where('cod_set_replicado', $vinculo['codset'])->first()) {
                Setor::vincularPessoa($setor, $user, mb_convert_case($vinculo['tipvin'], MB_CASE_TITLE));
                $log .= 'codpes=' . $user->codpes . ',setor=' . $setor->sigla . ',vínculo=' . $vinculo['tipvin'] . ';';
            }
        }

        config('app.debug') && Log::info($log);
    }
}
