<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * Rotas Publicas
 */

Route::prefix('auth')
    ->controller(\App\Http\Controllers\Auth\AuthController::class)
    ->group(function () {
        Route::post('login', 'login');
    });

/**
 * Rotas Privadas
 */


Route::prefix('auth')
    ->middleware('auth:sanctum')->group(function () {
        Route::controller(\App\Http\Controllers\Auth\AuthController::class)
            ->group(function () {
                Route::get('logged', 'isLogged');
                Route::get('logout', 'logout');
            });

        /**
         * Trocar Senha Controller
         */
        Route::controller(\App\Http\Controllers\TrocarSenhaUserController::class)
            ->group(function () {
                Route::put('/trocar-senha', 'trocarSenha');
            });
    });

Route::middleware(['auth:sanctum', 'role:admin|user'])
    ->group(function() {

        /**
         * Clientes Controller
         */
        Route::controller(\App\Http\Controllers\ClientesController::class)
            ->group(function () {
                Route::get('/clientes', 'index');
                Route::get('/cliente/{id}', 'show');
                Route::post('/cliente', 'store');
                Route::put('/cliente/{id}', 'update');
            });

        /**
         * Buffets Controller
         */
        Route::controller(\App\Http\Controllers\BuffetController::class)
            ->group(function () {
                Route::get('/buffets', 'index');
                Route::get('/buffet/{id}', 'show');
                Route::post('/buffet', 'store');
                Route::put('/buffet/{id}', 'update');
            });

        /**
         * Assessoria Controller
         */
        Route::controller(\App\Http\Controllers\AssessoriaController::class)
            ->group(function () {
                Route::get('/assessorias', 'index');
                Route::get('/assessoria/{id}', 'show');
                Route::post('/assessoria', 'store');
                Route::put('/assessoria/{id}', 'update');
            });

        /**
         * Busca CEP Controller
         */
        Route::controller(\App\Http\Controllers\BuscaCepController::class)
            ->group(function () {
                Route::get('/busca-cep', 'buscaCep');
            });
    });


/**
 * Rotas Admin
 */

Route::prefix('admin')
    ->middleware(['auth:sanctum','role:admin'])
    ->group(function () {
        /**
         * Usuário Controller
         */
        Route::controller(\App\Http\Controllers\Admin\UserController::class)
            ->group(function () {
                Route::get('/usuarios/', 'index');
                Route::get('/usuario/{id}', 'show');
                Route::post('/usuario/', 'store');
                Route::put('/usuario/{id}', 'update');
                Route::put('/usuario-redefinir-senha/{id}', 'redefinirSenha');
            });

        /**
         * Roles Controller *
         */
        Route::controller(\App\Http\Controllers\Admin\RoleController::class)
            ->group(function () {
                Route::get('/roles', 'index');
                Route::post('/role', 'store');
            });

        /**
         * Dados Empresa Controller
         */
        Route::controller(\App\Http\Controllers\Admin\DadosEmpresaController::class)
            ->group(function () {
                Route::get('/dados-empresa', 'show');
                Route::put('/dados-empresa', 'update');
            });
    });
