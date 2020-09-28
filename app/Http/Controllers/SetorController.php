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
        $data = [
            'title' => 'Setores',
            'url' => 'setores', // caminho da rota do resource
            'rows' => Setor::orderBy('setores_id', 'asc')->get(),
            'showId' => true,
            'fields' => Setor::fields,
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
        //return view('users.create');
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
        $request->validate(Setor::rules);

        Setor::create($request->all());

        $request->session()->flash('alert-info', 'Setor adicionado com sucesso');
        return redirect('/setores');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('admin');
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
        $this->authorize('admin');
        $request->validate(Setor::rules);

        $setor = Setor::find($id);
        $setor->fill($request->all());
        $setor->save();

        $request->session()->flash('alert-info', 'Setor editado com sucesso');
        return redirect('/setores');
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
