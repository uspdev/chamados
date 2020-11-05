<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
        
        // atualizar o telefone com a senha unica
        $user->telefone = $userSenhaUnica->telefone;

        $admins_id = explode(',', config('chamados.admins'));
        if (in_array($userSenhaUnica->codpes, $admins_id)) {
            $user->is_admin = true;
        }

        // se fora do replicado, bind dos dados
        if (!config('chamados.usar_replicado')) {
            $user->email = $userSenhaUnica->email;
            $user->name = $userSenhaUnica->nompes;
        }
        $user->last_login_at = now();

        $user->save();
        Auth::login($user, true);
        session(['perfil' => 'usuario']);
        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}
