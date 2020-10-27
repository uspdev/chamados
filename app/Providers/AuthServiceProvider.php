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
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        # admin
        Gate::define('admin', function ($user) {
            if (session('perfil') != 'admin') {
                return false;
            } else {
                return $user->is_admin;
            }
        });

        Gate::define('atendente', function ($user) {
            if (session('perfil') == 'atendente') {
                return true;
            } else {
                return false;
            }
        });

        Gate::define('usuario', function ($user) {
            if (session('perfil') == 'usuario' || empty(session('perfil'))) {
                return true;
            } else {
                return false;
            }
        });

        Gate::define('trocarPerfil', function($user) {
            return $user->is_admin ?? (session('perfil') == 'atendente');
        });

        # policies
        Gate::resource('chamados', 'App\Policies\ChamadoPolicy');
        //Gate::resource('comentarios', 'App\Policies\ComentarioPolicy');
    }
}
