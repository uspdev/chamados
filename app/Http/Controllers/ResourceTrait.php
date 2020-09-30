<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

trait ResourceTrait
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('admin');

        $this->data['fields'] = $this->model::getFields();
        $this->data['rows'] = $this->model::get();
        return view($this->data['url'] . '.index')->with('data', (object) $this->data);
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
        $request->validate($this->model::rules);

        $this->model::create($request->all());

        $request->session()->flash('alert-info', 'Dados adicionados com sucesso');
        return redirect('/' . $this->data['url']);
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
        return $this->model::find($id);
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
        $request->validate($this->model::rules);

        $setor = $this->model::find($id);
        $setor->fill($request->all());
        $setor->save();

        $request->session()->flash('alert-info', 'Dados editads com sucesso');
        return redirect('/' . $this->data['url']);
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
        $setor = $this->model::find($id);
        $sigla = $setor->sigla;
        $setor->delete();
        $request->session()->flash('alert-success', 'Dados removidos com sucesso!');
        return redirect('/' . $this->data['url']);
    }
}
