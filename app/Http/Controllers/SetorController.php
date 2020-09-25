<?php

namespace App\Http\Controllers;

use App\Models\Setor;
use Illuminate\Http\Request;

class SetorController extends Controller
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
        $this->authorize('admin');

        $setores = Setor::all();

        #return $setores;
        $data = [
            'title' => 'Setores',
            'rows' => $setores,
            'showId' => true,
            'fields' => [
                [
                    'name' => 'sigla',
                    'label' => 'Sigla',
                ],
                [
                    'name' => 'nome',
                    'label' => 'Nome',
                ],
                [
                    'name' => 'setores_id',
                    'label' => 'Pai',
                    'type' => 'number',
                ],
            ],
        ];
        return view('setores.index')->with('data', (object) $data);
    }

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
        $user = new User;

        $request->validate([
            'numero_usp' => ['required', new Numeros_USP($request->numero_usp)],
        ]);
        $user->codpes = $request->numero_usp;
        $user->email = Pessoa::email($request->numero_usp);
        $user->name = Pessoa::dump($request->numero_usp)['nompesttd'];
        $user->save();
        $request->session()->flash('alert-info', 'Atendente adicionado com sucesso');
        return redirect('/users');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return Setor::find($id);
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
        $setor = Setor::find($id);
        $sigla = $setor->sigla;
        $setor->delete();
        $request->session()->flash('alert-success', 'Setor ' . $sigla . ' removido com sucesso!');
        return redirect()->route('setores.index');
    }
}
