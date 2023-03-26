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

        Route::controller(\App\Http\Controllers\ListarController::class)
            ->group(function () {
                Route::get('listar-produtos', 'listarProdutos');
                Route::get('listar-vendedores', 'listarVendedores');

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

        /**
         * Colaborador Controller
         */
        Route::controller(\App\Http\Controllers\Admin\ColaboradorController::class)
            ->group(function () {
                Route::get('/colaboradores', 'index');
                Route::get('/colaborador/{id}', 'show');
                Route::post('/colaborador', 'store');
                Route::put('/colaborador/{id}', 'update');
            });

        /**
         * Colaborador Função Controller
         */
        Route::controller(\App\Http\Controllers\Admin\FuncaoController::class)
            ->group(function() {
                Route::get('colaborador-funcao', 'index');
            });

        /**
         * Produtos Controller
         */
        Route::controller(\App\Http\Controllers\Admin\ProdutoController::class)
            ->group(function () {
                Route::get('/produtos', 'index');
                Route::get('/produto/{id}', 'show');
                Route::post('/produto', 'store');
                Route::put('/produto/{id}', 'update');
            });

        /**
         * Pagamentos
         */
        Route::prefix('pagamento')
            ->group(function () {

                /**
                 * Forma Pagamento Controller
                 */
                Route::controller(\App\Http\Controllers\Admin\PagamentoFormasPagamentosController::class)
                    ->group(function () {
                        Route::get('/formas', 'index');
                        Route::get('/formas-pagamento', 'listaFormaPagamento');
                        Route::get('/forma/{id}', 'show');
                        Route::post('/forma', 'store');
                        Route::put('/forma/{id}', 'update');
                    });

                /**
                 * Condicao Pagamento Controller
                 */
                Route::controller(\App\Http\Controllers\Admin\PagamentoCondicaoPagamentosController::class)
                    ->group(function () {
                        Route::get('/condicoes', 'index');
                        Route::get('/condicao/{id}', 'show');
                        Route::post('/condicao', 'store');
                        Route::put('/condicao/{id}', 'update');
                    });
            });
    });
