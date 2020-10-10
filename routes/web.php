<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ChamadoController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\SetorController;
use App\Http\Controllers\FilaController;
use App\Http\Controllers\UserController;


Route::get('/', [IndexController::class, 'index'])->name('home');

/* Senha única */
Route::get('login', [LoginController::class, 'redirectToProvider'])->name('login');
Route::get('callback', [LoginController::class,'handleProviderCallback']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Admin resources
Route::resource('setores', SetorController::class);

Route::resource('filas', FilaController::class);
Route::post('filas/{fila}/pessoas', [FilaController::class,'storePessoa']);
Route::delete('filas/{fila}/pessoas/{id}', [FilaController::class,'destroyPessoa']);

Route::get('search/partenome', [UserController::class,'partenome']);

Route::resource('users', UserController::class);

/**/
Route::get('chamados/create', [ChamadoController::class, 'listaFilas']);
Route::get('chamados/create/{fila}/', [ChamadoController::class, 'create'])->name('chamados.create');
Route::post('chamados/create/{fila}/', [ChamadoController::class, 'store'])->name('chamados.store');
Route::resource('chamados', ChamadoController::class)->except(['create', 'store']);
Route::resource('comentarios/{chamado}/', ComentarioController::class);

Route::get('atender', [ChamadoController::class, 'atender']);
Route::get('triagem', [ChamadoController::class,'triagem']);
Route::get('todos', [ChamadoController::class,'todos']);
Route::get('buscaid', [ChamadoController::class,'buscaid']);
Route::get('chamados/{chamado}/devolver', [ChamadoController::class,'devolver']);

Route::get('triagem/{chamado}', [ChamadoController::class,'triagemForm']);
Route::post('triagem/{chamado}', [ChamadoController::class,'triagemStore']);
