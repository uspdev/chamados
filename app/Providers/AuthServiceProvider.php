<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        #Chamado::Class => ChamadoPolicy::class,
        #Fila::Class => FilaPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin', function ($user) {
            return $user->is_admin;
        });

        Gate::define('atendente', function ($user) {
            return $user->filas()->count() || $user->is_admin;
        });

        Gate::define('usuario', function ($user) {
            return $user;
        });

        # perfis
        # o perfil é o modo como o usuário se apresenta
        # ideal para mostrar os menus e a lista de chamados
        Gate::define('perfiladmin', function ($user) {
            if (session('perfil') != 'admin') {
                return false;
            } else {
                return $user->is_admin;
            }
        });

        Gate::define('perfilatendente', function ($user) {
            if (session('perfil') == 'atendente') {
                return true;
            } else {
                return false;
            }
        });

        Gate::define('perfilusuario', function ($user) {
            if (session('perfil') == 'usuario' || empty(session('perfil'))) {
                return true;
            } else {
                return false;
            }
        });

        
        Gate::define('trocarPerfil', function ($user) {
            return Gate::any(['admin', 'atendente']);
        });

        # se o admin assumir identidade de outro usuário, permite retornar
        Gate::define('desassumir', function ($user) {
            return session('adminCodpes');
        });

        # policies
        Gate::resource('chamados', 'App\Policies\ChamadoPolicy');
        Gate::define('chamados.updateComentario', 'App\Policies\ChamadoPolicy@updateComentario');
        Gate::define('chamados.permitePessoasFila', 'App\Policies\ChamadoPolicy@permitePessoasFila');

        Gate::resource('filas', 'App\Policies\FilaPolicy');
        Gate::define('filas.atendente', 'App\Policies\FilaPolicy@atendente');

        Gate::resource('setores', 'App\Policies\SetorPolicy');
        Gate::resource('users', 'App\Policies\UserPolicy');
    }
}
