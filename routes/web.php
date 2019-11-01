<?php
Route::get('/', 'IndexController@index')->name('home');

/* Senha única */
Route::get('login', 'Auth\LoginController@redirectToProvider')->name('login');
Route::get('callback', 'Auth\LoginController@handleProviderCallback');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

/**/
Route::resource('categorias', 'CategoriaController');
Route::resource('chamados', 'ChamadoController');
Route::resource('comentarios/{chamado}/', 'ComentarioController');

Route::get('atender', 'ChamadoController@atender');
Route::get('triagem', 'ChamadoController@triagem');
Route::get('todos', 'ChamadoController@todos');

Route::get('triagem/{chamado}', 'ChamadoController@triagemForm');
Route::post('triagem/{chamado}', 'ChamadoController@triagemStore');
