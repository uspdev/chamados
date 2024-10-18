<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('users.viewAny');
        \UspTheme::activeUrl('users');

        $users = User::all();
        return view('users.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('admin');
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('admin');
        User::obterOuCriarPorCodpes($request->codpes);
        $request->session()->flash('alert-info', 'Atendente adicionado com sucesso');
        return redirect('/users');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize('users.view', $user);
        \UspTheme::activeUrl('senhaunica-users');

        $oauth_file = 'debug/oauth/' . $user->codpes . '.json';

        $oauth['data'] = '';
        $oauth['time'] = '';
        if (Storage::disk('local')->exists($oauth_file)) {
            $oauth['data'] = Storage::disk('local')->get($oauth_file);
            $oauth['time'] = Storage::lastModified($oauth_file);
        }
        return view('users.show', compact('user', 'oauth'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = \Auth::user();
        $requests = $request->all();

        # vamos atualizar as notificações
        if (isset($requests['emailNotification'])) {
            # usar update() não seta o isDirty(), por isso o uso de fill
            $user->fill(['config->notifications->email' => $requests['emailNotification']]);
            if ($user->isDirty()) {
                $user->save();
                $request->session()->flash('alert-info', 'Notificações atualizadas com sucesso.');
            } else {
                $request->session()->flash('alert-info', 'Nada modificado.');
            }
        } else {
            $request->session()->flash('alert-info', 'Nada modificado.');
        }
        return Redirect::to(URL::previous() . "#card_notificacoes");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->authorize('admin');
        $user = User::find($id);
        $user->delete();
        $request->session()->flash('alert-success', 'Dados removidos com sucesso!');
        return back();
    }

    /**
     * Permite fazer buscas ajax por nome, formatado para datatables
     */
    public function partenome(Request $request)
    {
        $this->authorize('usuario');
        if ($request->term) {
            $results = [];
            if (config('chamados.usar_replicado')) {
                $pessoas = \Uspdev\Replicado\Pessoa::procurarPorNome($request->term);
                // limitando a resposta em 50 elementos
                $pessoas = array_slice($pessoas, 0, 50);

                // formatando para select2
                foreach ($pessoas as $pessoa) {
                    $results[] = [
                        'text' => $pessoa['codpes'] . ' ' . $pessoa['nompesttd'],
                        'id' => $pessoa['codpes'],
                    ];
                }
            }

            # mesmo pegando do replicado vamos pegar da base local também
            $pessoas = User::where('name', 'like', '%' . $request->term . '%')->get()->take(1);
            foreach ($pessoas as $pessoa) {
                $results[] = [
                    'text' => $pessoa->codpes . ' ' . $pessoa->name,
                    'id' => "$pessoa->codpes",
                ];
            }

            # removendo duplicados
            $results = array_map("unserialize", array_unique(array_map("serialize", $results)));

            # vamos regerar o indice. Pode ser que tenha jeito melhor de eliminar duplicados
            $results = array_values($results);

            return response(compact('results'));
        }
    }

    /**
     * Permite trocar o perfil do usuário: admin, atendente ou usuário comuum
     */
    public function trocarPerfil(Request $request, $perfil)
    {
        $this->authorize('trocarPerfil');
        $ret = Auth::user()->trocarPerfil($perfil);
        if ($ret['success']) {
            $request->session()->flash('alert-info', $ret['msg']);
        }
        return back();
    }

    /**
     * Permite assumir a identidade de outro usuário
     */
    public function assumir(User $user)
    {
        $this->authorize('admin');

        session(['adminCodpes' => \Auth::user()->codpes]);
        \Auth::login($user, true);
        session(['perfil' => 'usuario']);

        return redirect('/');
    }

    /**
     * Permite retornar a identidade original
     */
    public function desassumir()
    {
        $this->authorize('desassumir');

        $user = User::obterPorCodpes(session('adminCodpes'));
        session(['adminCodpes' => 0]);
        \Auth::login($user, true);
        session(['perfil' => 'admin']);

        return redirect('/');
    }

    /**
     * Redireciona para o perfil do usuário.
     *
     * Foi criado para poder colocar o link no menu.
     */
    public function meuperfil()
    {
        return redirect('users/' . \Auth::user()->id);
    }
}
