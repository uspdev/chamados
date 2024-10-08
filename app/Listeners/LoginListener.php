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

        // vincular a pessoa e o vinculo ao setor
        foreach ($vinculos as $vinculo) {
            if ($setor = Setor::where('cod_set_replicado', $vinculo['codset'])->first()) {
                Setor::vincularPessoa($setor, $user, mb_convert_case($vinculo['tipvin'], MB_CASE_TITLE));
                $log .= 'update vinculo codpes=' . $user->codpes . ',setor=' . $setor->sigla . ',vínculo=' . $vinculo['tipvin'] . ';';
            }
        }

        // vamos manter a configuracao antiga para compatibilidade retroativa
        // mas deverá ser ajustado e removido as referências a "is_admin"
        // vamos verificar no config se o usuário é admin
        //$admins_codpes = explode(',', config('senhaunica.admins'));
        if (in_array($user->codpes, config('senhaunica.admins'))) {
            $user->is_admin = true;
        }

        // atualizando o last login do usuário
        $user->last_login_at = now();
        $user->save();

        // será que precisa disso?
        //session(['perfil' => 'usuario']);

        config('app.debug') && Log::info($log);
    }
}
