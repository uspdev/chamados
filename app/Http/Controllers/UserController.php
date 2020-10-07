<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Uspdev\Replicado\Pessoa;

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
        $user = new User;
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
}
