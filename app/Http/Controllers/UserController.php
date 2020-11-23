<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ResourceTrait;

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
        $this->authorize('admin');
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
        User::storeByCodpes($request->codpes);
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
        return view('users.show', compact('user'));
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
        //
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
            else {
                $pessoas = User::where('name', 'like', '%'.$request->term.'%')->get()->take(1);

                foreach ($pessoas as $pessoa) {
                    $results[] = [
                        'text' => $pessoa->codpes . ' ' . $pessoa->name,
                        'id' => $pessoa->codpes
                    ];
                }
            }
            return response(compact('results'));
        }
    }

    /**
     * Permite trocar o perfil do usuário: admin, atendente ou usuário comuum
     */
    public function trocarPerfil(Request $request, $perfil)
    {
        $this->authorize('trocarPerfil');
        switch ($perfil) {
            case 'usuario':
                session(['perfil' => 'usuario']);
                $request->session()->flash('alert-info', 'Perfil mudado para Usuário com sucesso.');
                break;

            case 'atendente':
                $this->authorize('atendente');
                session(['perfil' => 'atendente']);
                $request->session()->flash('alert-info', 'Perfil mudado para Atendente com sucesso.');
                break;

            case 'admin':
                $this->authorize('admin');
                session(['perfil' => 'admin']);
                $request->session()->flash('alert-info', 'Perfil mudado para Admin com sucesso.');
                break;
        }
        return back();
    }

    /**
     * Permite assumir a identidade de outro usuário
     */
    public function assumir(User $user)
    {
        $this->authorize('admin');

        \Auth::login($user, true);
        session(['perfil' => 'usuario']);
        return redirect('/');
    }
}
