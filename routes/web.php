<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [\App\Http\Controllers\UsuarioController::class, 'login'])->name('login');
Route::post('/login', [\App\Http\Controllers\UsuarioController::class, 'verificar'])->name('verificar');

Route::get('/registrarse', [\App\Http\Controllers\UsuarioController::class, 'registrarse'])->name('registrarse');
Route::post('/registrarse', [\App\Http\Controllers\UsuarioController::class, 'registro'])->name('registro');

Route::get('/salir', [\App\Http\Controllers\UsuarioController::class, 'salir'])->name('salir');

Route::get('/aceptarSolicitudes/{idsolicitud}', [\App\Http\Controllers\AmigosController::class, 'aceptarSolicitudes'])->name('aceptarSolicitudes');

Route::middleware('VerificarUsuario')->group(function (){
    Route::get('/home', [\App\Http\Controllers\UsuarioController::class, 'home'])->name('home');
    Route::post('/home', [\App\Http\Controllers\PublicacionesController::class, 'publicaciones'])->name('publicaciones');

    Route::post('/like/{idpost?}', [\App\Http\Controllers\PublicacionesController::class, 'like'])->name('like');
    Route::post('/comentar/{idpost?}', [\App\Http\Controllers\ComentariosController::class, 'comentar'])->name('comentar');

    Route::get('/perfil', [\App\Http\Controllers\UsuarioController::class, 'perfil'])->name('perfil');
    Route::post('/perfil', [\App\Http\Controllers\UsuarioController::class, 'actualizarPerfil'])->name('actualizarPerfil');
    Route::get('/perfil/{idusuario}', [\App\Http\Controllers\AmigosController::class, 'verPerfiles'])->name('verPerfiles');
    Route::get('/descargarInfo', [\App\Http\Controllers\UsuarioPDFController::class, 'descargarInfo'])->name('descargarInfo');

    Route::get('/usuarios', [\App\Http\Controllers\UsuarioController::class, 'usuarios'])->name('usuarios');
    Route::post('/addAmigo/{idusuario?}', [\App\Http\Controllers\AmigosController::class, 'addAmigoCorreo'])->name('addAmigo');
});

