<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Uspdev\Replicado\Pessoa;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'codpes', 'telefone', 'last_login_at',
    ];

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
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
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

    public static function storeByCodpes($codpes) {
        $user = new User;
        $user->codpes = $codpes;
        $user->email = Pessoa::email($codpes);
        $user->name = Pessoa::dump($codpes)['nompesttd'];
        $user->save();
        return $user;
    }

    public static function getByCodpes($codpes) {
        return User::where('codpes',$codpes)->first();
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
     * Relacionamento n:n com chamado, atributo funcao:
     * - Atendente, Atribuidor, Autor, Observador
     */
    public function chamados()
    {
        return $this->belongsToMany('App\Models\Chamado', 'user_chamado')
            ->withPivot('funcao')
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
