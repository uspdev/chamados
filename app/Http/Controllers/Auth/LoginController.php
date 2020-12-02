<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setor;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider()
    {
        return Socialite::driver('senhaunica')->redirect();
    }

    public function handleProviderCallback()
    {
        $userSenhaUnica = Socialite::driver('senhaunica')->user();
        $user = User::obterOuCriarPorCodpes($userSenhaUnica->codpes);

        // atualizar dados com a senha unica
        // assim se houver atualizações na uSP é refletido aqui
        $user->telefone = $userSenhaUnica->telefone;
        $user->email = $userSenhaUnica->email;
        $user->name = $userSenhaUnica->nompes;

        // vamos verificar no config se o usuário é admin
        $admins_codpes = explode(',', config('chamados.admins'));
        if (in_array($userSenhaUnica->codpes, $admins_codpes)) {
            $user->is_admin = true;
        }

        $user->last_login_at = now();
        $user->save();

        # vincular a pessoa e o vinculo ao setor
        foreach ($userSenhaUnica->vinculo as $vinculo) {
            if ($setor = Setor::where('cod_set_replicado', $vinculo['codigoSetor'])->first()) {
                Setor::vincularPessoa($setor, $user, $vinculo['nomeVinculo']);
            }
        }

        Auth::login($user, true);
        session(['perfil' => 'usuario']);
        return redirect()->intended('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}
