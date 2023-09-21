<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\SetorController;
use App\Http\Controllers\ArquivoController;
use App\Http\Controllers\ChamadoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\PatrimonioController;

Route::get('/', [IndexController::class, 'index'])->name('home');
Route::get('ajuda', [IndexController::class, 'ajuda']);
Route::get('ajuda/manual_usuario', [IndexController::class, 'manual_usuario']);
Route::get('ajuda/manual_atendente', [IndexController::class, 'manual_atendente']);

/* Senha única */
// Route::get('login', [LoginController::class, 'redirectToProvider'])->name('login');
// Route::get('callback', [LoginController::class, 'handleProviderCallback']);
// Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// SETORES
Route::post('setores/{setor}/pessoas', [SetorController::class, 'storePessoa']);
Route::delete('setores/{setor}/pessoas/{id}', [SetorController::class, 'destroyPessoa']);
Route::resource('setores', SetorController::class);

// FILAS
Route::resource('filas', FilaController::class)->except(['create', 'destroy', 'edit']);
#Route::post('filas/{fila}/estado', [FilaController::class,'updateStatus']);
Route::post('filas/{fila}/pessoas', [FilaController::class, 'storePessoa']);
Route::delete('filas/{fila}/pessoas/{id}', [FilaController::class, 'destroyPessoa']);
Route::post('filas/{fila}/template_json', [FilaController::class, 'storeTemplateJson']);
Route::get('filas/{fila}/template', [FilaController::class, 'createTemplate'])->name('filas.createtemplate');
Route::post('filas/{fila}/template', [FilaController::class, 'storeTemplate'])->name('filas.storetemplate');
Route::get('filas/{fila}/download', [FilaController::class, 'download'])->name('filas.download');

// USERS
Route::get('search/partenome', [UserController::class, 'partenome']);
Route::get('users/perfil/{perfil}', [UserController::class, 'trocarPerfil']);
Route::get('users/desassumir', [UserController::class, 'desassumir']);
Route::get('users/{user}/assumir', [UserController::class, 'assumir']);
Route::get('users/meuperfil', [UserController::class, 'meuperfil']);
Route::resource('users', UserController::class);

// CHAMADOS - VINCULADOS
Route::get('chamados/listarChamadosAjax', [ChamadoController::class, 'listarChamadosAjax']);
Route::post('chamados/{chamado}/vinculado', [ChamadoController::class, 'storeChamadoVinculado']);
Route::delete('chamados/{chamado}/vinculado/{id}', [ChamadoController::class, 'deleteChamadoVinculado']);

// CHAMADOS - ANOS
Route::get('chamados/anos/{ano}', [ChamadoController::class, 'mudaAno']);

// CHAMADOS - USERS
Route::post('chamados/{chamado}/pessoas', [ChamadoController::class, 'storePessoa']);
Route::delete('chamados/{chamado}/pessoas/{user}', [ChamadoController::class, 'destroyPessoa']);

// CHAMADOS - PATRIMONIOS
Route::get('chamados/listarPatrimoniosAjax', [ChamadoController::class, 'listarPatrimoniosAjax']);
Route::post('chamados/{chamado}/patrimonios', [ChamadoController::class, 'storePatrimonio']);
Route::delete('chamados/{chamado}/patrimonios/{patrimonio}', [ChamadoController::class, 'destroyPatrimonio']);

// CHAMADOS - TRIAGEM
Route::post('chamados/{chamado}/triagem', [ChamadoController::class, 'triagemStore']);

// CHAMADOS
Route::get('chamados/create', [ChamadoController::class, 'listaFilas']);
Route::get('chamados/create/{fila}/', [ChamadoController::class, 'create'])->name('chamados.create');
Route::post('chamados/create/{fila}/', [ChamadoController::class, 'store'])->name('chamados.store');
Route::resource('chamados', ChamadoController::class)->except(['create', 'store']);

// COMENTARIOS
Route::post('comentarios/{chamado}/', [ComentarioController::class, 'store']);

// ARQUIVOS
Route::resource('arquivos', ArquivoController::class);

// ADMIN
Route::get('admin', [AdminController::class, 'index']);
Route::get('admin/get_oauth_file/{filename}', [AdminController::class, 'getOauthFile']);

// PATRIMONIOS
Route::resource('patrimonios', PatrimonioController::class);
