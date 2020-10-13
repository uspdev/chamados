<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ResourceTrait;

    protected $model = 'App\Models\User';

    protected $data = [
        'title' => 'Usuários',
        'url' => 'users', // caminho da rota do resource
        'showId' => true, // default true
        'editBtn' => false, // default true
        'modal' => false, // default false
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $users = User::all();
    //     return view('users.index')->with('users', $users);
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        User::storeByCodpes($request->numero_usp);
        $request->session()->flash('alert-info', 'Atendente adicionado com sucesso');
        return redirect('/users');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = $this->model::find($id);
        $user->delete();
        $request->session()->flash('alert-success', 'Dados removidos com sucesso!');
        return redirect('/' . $this->data['url']);
    }

    public function partenome(Request $request)
    {
        $this->authorize('admin');
        if ($request->term) {
            $pessoas = \Uspdev\Replicado\Pessoa::nomeFonetico($request->term);
            $pessoas = array_slice($pessoas, 0, 50);

            // formatando para select2
            $results = [];
            foreach ($pessoas as $pessoa) {
                $results[] = [
                    'text' => $pessoa['codpes'] . ' ' . $pessoa['nompesttd'],
                    'id' => $pessoa['codpes'],
                ];
            }
            $out['results'] = $results;
            // limitando a resposta em 50 elementos
            return response($out);
        }
    }

    public function trocarPerfil(Request $request, $perfil)
    {
        $this->authorize('trocarPerfil');
        switch ($perfil) {
            case 'usuario':
                session(['is_admin' => 0]);
                $request->session()->flash('alert-info', 'Perfil mudado para Usuário com sucesso.');
                break;
            case 'admin':
                session(['is_admin' => 1]);
                $request->session()->flash('alert-info', 'Perfil mudado para Admin com sucesso.');
                break;
        }
        return back();
        echo $perfil;

    }
}
