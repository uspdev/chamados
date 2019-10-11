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
            $admins_id = explode(',', config('atendimento.admins'));
            return in_array($user->codpes, $admins_id);
        });

        # atendente 
        Gate::define('atendente', function ($user) {
            $atendentes = explode(',', config('atendimento.atendentes'));
            return in_array($user->codpes, $atendentes);
        });


        # policies
        Gate::resource('chamados', 'App\Policies\ChamadoPolicy');
        //Gate::resource('comentarios', 'App\Policies\ComentarioPolicy');
    }
}
