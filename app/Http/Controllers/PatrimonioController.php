<?php

namespace App\Http\Controllers;

use App\Models\Patrimonio;
use Illuminate\Http\Request;

class PatrimonioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        \UspTheme::activeUrl('patrimonios');
        $this->authorize('perfiladmin');
        $patrimonios = Patrimonio::all();

        return view('patrimonios.index', compact('patrimonios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $numpat
     * @return \Illuminate\Http\Response
     */
    public function show($numpat)
    {
        \UspTheme::activeUrl('patrimonios');
        $this->authorize('perfiladmin');

        $patrimonio = Patrimonio::where('numpat', $numpat)->first();
        return view('patrimonios.show', compact('patrimonio'));
        dd($patrimonio->responsavel(), $patrimonio->marcaModeloTipo());
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
    public function destroy($id)
    {
        //
    }
}
