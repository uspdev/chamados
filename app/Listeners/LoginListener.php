<?php

namespace App\Listeners;

use App\Models\Setor;
use Uspdev\Replicado\Pessoa;
use Uspdev\Replicado\Estrutura;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class LoginListener
{
    public function __construct() {}

    public function handle(Login $event)
    {
        $user = $event->user;

        try {
            $userSenhaUnica = Socialite::driver('senhaunica')->user();
            $userSenhaUnica = $userSenhaUnica->attributes;
            $user->telefone = $userSenhaUnica['telefone'];
            $user->email = $userSenhaUnica['email'];
        } catch (\Exception $e) {
            // mitigando erro do auth verifier fora do fluxo de login
        }

        $log = 'login listener:';

        // vincular a pessoa e o vinculo ao setor
        $vinculos = Pessoa::listarVinculosAtivos($user->codpes, false);
        foreach ($vinculos as $vinculo) {
            $setor = Setor::where('cod_set_replicado', $vinculo['codset'])->first();
            if ($setor) {
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
