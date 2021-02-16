<?php

namespace App\Http\Controllers;

use App\Models\Setor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class SetorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostra lista de setores de acordo com as permissões do usuário
     */
    public function index(Request $request)
    {
        $this->authorize('setores.viewAny');
        
        $user = \Auth::user();

        if (Gate::allows('perfilAdmin')) {
            # se for admin mostra tudo
            $setor = Setor::where('setor_id', null)->first();
        } else {
            # se o usuário é gerente de um setor, mostra ele e os descendentes
            $setor = $user->setores()->first();
        }
        $fields = Setor::getFields();

        # para o form de adicionar pessoas
        $modal_pessoa['url'] = 'setores';
        $modal_pessoa['title'] = 'Adicionar pessoa';

        if ($request->ajax()) {
            // formatado para datatables
            #return response(['data' => $setores]);
        } else {
            $modal['url'] = 'setores';
            $modal['title'] = 'Editar setor';
            return view('setores.tree', compact('setor', 'fields', 'modal', 'modal_pessoa', 'user'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        #usando no ajax, somente para admin
        $this->authorize('admin');

        if ($request->ajax()) {
            # preenche os dados do form de edição de um setor
            return Setor::find($id);
        } else {
            # desativado por enquanto
            return false;
            $setor = Setor::find($id);
            return view('setores.show', compact('setor'));
        }
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

        $setor = Setor::create($request->all());

        $request->session()->flash('alert-info', 'Dados adicionados com sucesso');
        return Redirect::to(URL::previous() . "#" . strtolower($setor->sigla));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * Por enquanto somente para admin (masaki, 12/2020)
     * Talvez, autorizar se usuário for gerente do setor ou de qualquer setor acendente
     */
    public function update(Request $request, $id)
    {
        $this->authorize('admin');
        $request->validate(Setor::rules);

        $setor = Setor::find($id);
        $setor->fill($request->all());
        $setor->save();

        $request->session()->flash('alert-info', 'Dados editados com sucesso');
        return Redirect::to(URL::previous() . "#" . strtolower($setor->sigla));
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
        $setor->delete();

        $request->session()->flash('alert-success', 'Dados removidos com sucesso!');
        return back();
    }

    /**
     * Adiciona pessoas como gerente do setor
     */
    public function storePessoa(Request $request, Setor $setor)
    {
        $this->authorize('setores.view', $setor);

        $user = User::obterOuCriarPorCodpes($request->codpes);
        Setor::vincularPessoa($setor, $user, 'Gerente');

        $request->session()->flash('alert-info', 'Pessoa adicionada com sucesso');

        #vamos retornar inclundo anchor
        return Redirect::to(URL::previous() . "#" . strtolower($setor->sigla));
    }

    /**
     * Remove pessoas que sao gerentes do setor
     */
    public function destroyPessoa(Request $request, Setor $setor, $id)
    {
        $this->authorize('setores.view', $setor);

        $currentUser = \Auth::user();

        # remover a si mesmo pode se for admin, ok
        # também tem de poder se a pessoa estiver em um setor pai, assim
        # não perde o acesso ao setor atual, como fazer??
        if ($currentUser->id == $id && $currentUser->is_admin == false) {
            $request->session()->flash('alert-warning', 'Não é possível remover a si mesmo.');
            return back();
        }
        $setor->users()->wherePivot('funcao', 'Gerente')->detach($id);
        $request->session()->flash('alert-info', 'Pessoa removida com sucesso');
        return Redirect::to(URL::previous() . "#" . strtolower($setor->sigla));

    }

}
