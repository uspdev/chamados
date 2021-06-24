<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Uspdev\Replicado\Pessoa;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    # desativado por enquanto por conta de conflito
    #use \Spatie\Permission\Traits\HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
        'codpes', 'telefone', 'last_login_at',
        'config',
        'config->notifications->email',
        'config->notifications->email->filas',
        'config->notifications->email->observador',
    ];

    # colocando data aqui ele já envia um objeto carbon
    protected $dates = ['last_login_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * O config está com o formato json no BD
     * https://laravel.com/docs/8.x/eloquent-mutators#array-and-json-casting
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'config' => 'array',
    ];

    public const rules = [
        'codpes' => 'required',
        'name' => 'required',
        'email' => 'email:rfc',
        'telefone' => '',
    ];

    protected const fields = [
        [
            'name' => 'codpes',
            'label' => 'Número USP',
        ],
        [
            'name' => 'name',
            'label' => 'Nome',
        ],
        [
            'name' => 'email',
            'label' => 'Email',
        ],
        [
            'name' => 'telefone',
            'label' => 'Telefone',
        ],
        [
            'name' => 'last_login_at',
            'label' => 'Ultimo login',
            'format' => 'timestamp',
        ],
        [
            'name' => 'is_admin',
            'label' => 'Admin',
            'format' => 'boolean',
        ],
    ];

    public static function getFields()
    {
        $fields = SELF::fields;
        return $fields;
        // foreach ($fields as &$field) {
        //     if (substr($field['name'], -3) == '_id') {
        //         $class = '\\App\\Models\\' . $field['model'];
        //         $field['data'] = $class::allToSelect();
        //     }
        // }
        // return $fields;
    }

    public static function criarPorCodpes($codpes)
    {
        $user = new User;
        $user->codpes = $codpes;
        if (config('chamados.usar_replicado')) {
            $user->email = Pessoa::email($codpes);
            $user->name = Pessoa::dump($codpes)['nompesttd'];
            $user->telefone = Pessoa::obterRamalUsp($codpes);
        } else {
            $user->email = $codpes . '@usuarios.usp.br';
            $user->name = $codpes;
        }
        $user->save();
        return $user;
    }

    public static function obterPorCodpes($codpes)
    {
        return User::where('codpes', $codpes)->first();
    }

    /**
     * Obtém se já existir ou cria um novo objeto de usuário
     *
     * @param Int $codpes Número USP a ser procurado ou criado
     * @return Obj $user Objeto do usuário criado
     */
    public static function obterOuCriarPorCodpes($codpes)
    {
        $user = User::obterPorCodpes($codpes);
        if (empty($user)) {
            $user = User::criarPorCodpes($codpes);
        }
        return $user;
    }

    /**
     * Relacionamento n:n com fila, atributo funcao:
     *  - Gerente, Atendente
     */
    public function filas()
    {
        return $this->belongsToMany('App\Models\Fila', 'user_fila')
            ->withPivot('funcao')
            ->withTimestamps();
    }

    /**
     * Relacionamento n:n com chamado, atributo papel:
     */
    public function chamados()
    {
        return $this->belongsToMany('App\Models\Chamado', 'user_chamado')
            ->withPivot('papel')
            ->withTimestamps();
    }

    /**
     * Relacionamento n:n com setor, atributo funcao:
     *  - Gerente, Usuario
     */
    public function setores()
    {
        return $this->belongsToMany('App\Models\Setor', 'user_setor')
            ->withPivot('funcao')
            ->withTimestamps();
    }
}
