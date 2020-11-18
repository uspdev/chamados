<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ChamadoController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\SetorController;
use App\Http\Controllers\FilaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArquivoController;

Route::get('/', [IndexController::class, 'index'])->name('home');
Route::get('ajuda', [IndexController::class, 'ajuda']);
Route::get('ajuda/manual_usuario', [IndexController::class, 'manual_usuario']);
Route::get('ajuda/manual_atendente', [IndexController::class, 'manual_atendente']);

/* Senha única */
Route::get('login', [LoginController::class, 'redirectToProvider'])->name('login');
Route::get('callback', [LoginController::class,'handleProviderCallback']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// SETORES
Route::post('setores/{setor}/pessoas', [SetorController::class,'storePessoa']);
Route::delete('setores/{setor}/pessoas/{id}', [SetorController::class,'destroyPessoa']);
Route::resource('setores', SetorController::class);

// FILAS
Route::resource('filas', FilaController::class);
#Route::post('filas/{fila}/estado', [FilaController::class,'updateStatus']);
Route::post('filas/{fila}/pessoas', [FilaController::class,'storePessoa']);
Route::delete('filas/{fila}/pessoas/{id}', [FilaController::class,'destroyPessoa']);
Route::post('filas/{fila}/template_json', [FilaController::class,'storeTemplateJson']);
Route::get('filas/{fila}/template', [FilaController::class, 'createTemplate'])->name('filas.createtemplate');
Route::post('filas/{fila}/template', [FilaController::class, 'storeTemplate'])->name('filas.storetemplate');

// USERS
Route::get('search/partenome', [UserController::class,'partenome']);
Route::resource('users', UserController::class);
Route::get('users/perfil/{perfil}', [UserController::class, 'trocarPerfil']);
Route::get('users/{user}/assumir', [UserController::class, 'assumir']);

// CHAMADOS - VINCULADOS
Route::get('chamados/listarChamadosAjax', [ChamadoController::class,'listarChamadosAjax']);
Route::post('chamados/{chamado}/vinculado', [ChamadoController::class,'storeChamadoVinculado']);
Route::delete('chamados/{chamado}/vinculado/{id}', [ChamadoController::class,'deleteChamadoVinculado']);

// CHAMADOS - ANOS
Route::get('chamados/anos/{ano}', [ChamadoController::class,'mudaAno']);

// CHAMADOS - USERS
Route::post('chamados/{chamado}/pessoas', [ChamadoController::class,'storePessoa']);
Route::delete('chamados/{chamado}/pessoas/{user}', [ChamadoController::class,'destroyPessoa']);

// CHAMADOS
Route::get('chamados/create', [ChamadoController::class, 'listaFilas']);
Route::get('chamados/create/{fila}/', [ChamadoController::class, 'create'])->name('chamados.create');
Route::post('chamados/create/{fila}/', [ChamadoController::class, 'store'])->name('chamados.store');
Route::resource('chamados', ChamadoController::class)->except(['create', 'store']);

#Route::get('chamados/{chamado}/devolver', [ChamadoController::class,'devolver']);

Route::post('triagem/{chamado}', [ChamadoController::class,'triagemStore']);

// COMENTARIOS
Route::resource('comentarios/{chamado}/', ComentarioController::class);

// ARQUIVOS
Route::resource('arquivos', ArquivoController::class);
