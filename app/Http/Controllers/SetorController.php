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
    use ResourceTrait;

    protected $model = 'App\Models\Setor';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        //$this->authorize('admin');

        if (Gate::allows('admin')) {
            $setor = Setor::where('setor_id', null)->first();
        } else {
            $user = \Auth::user();
            $setor = $user->setores()->first();
        }
        $fields = Setor::getFields();
        $user = \Auth()->user();

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
            //return view('setores.index', compact('setores', 'fields'));
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
        $request->validate($this->model::rules);

        $setor = Setor::create($request->all());

        $request->session()->flash('alert-info', 'Dados adicionados com sucesso');

        return Redirect::to(URL::previous() . "#".strtolower($setor->sigla));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * Por enquanto somente para admin
     * Talvez, autorizar se usuário for gerente do setor ou de qualquer setor acendente
     */
    public function update(Request $request, $id)
    {
        $this->authorize('admin');

        if (defined($this->model . '::rules')) {
            $request->validate($this->model::rules);
        }

        $setor = $this->model::find($id);
        $setor->fill($request->all());
        $setor->save();

        $request->session()->flash('alert-info', 'Dados editados com sucesso');
        return Redirect::to(URL::previous() . "#".strtolower($setor->sigla));
    }

    /**
     * Autorizar usuarios que sao gerentes do setor ou de setor acendente
     */
    public function storePessoa(Request $request, Setor $setor)
    {
        $user = User::obterOuCriarPorCodpes($request->codpes);
        $setor->users()->detach($user);
        $setor->users()->attach($user, ['funcao' => 'Gerente']);

        $request->session()->flash('alert-info', 'Pessoa adicionada com sucesso');

        #vamos retornar inclundo anchor
        return Redirect::to(URL::previous() . "#".strtolower($setor->sigla));
    }

    /**
     * Autorizar pessoas que sao gerentes do setor ou de setor acendente
     */
    public function destroyPessoa(Request $request, Setor $setor, $id)
    {
        $currentUser = \Auth::user();

        # remover a si mesmo pode se for admin, ok
        # também tem de poder se a pessoa estiver em um setor pai, assim
        # não perde o acesso ao setor atual, como fazer??
        if ($currentUser->id == $id && $currentUser->is_admin == false) {
            $request->session()->flash('alert-warning', 'Não é possível remover a si mesmo.');
            return back();
        }
        $setor->users()->detach($id);
        $request->session()->flash('alert-info', 'Pessoa removida com sucesso');
        return Redirect::to(URL::previous() . "#".strtolower($setor->sigla));

    }
    

}
