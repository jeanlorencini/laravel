<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContaController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\BasicAuthMiddleware;

// Rotas com autenticação HTTP Basic
Route::middleware([BasicAuthMiddleware::class])->group(function () {
    // Rota principal
    Route::get('/', function () {
        if (Auth::check()) {
            return redirect('/home');
        }
        return redirect('/login');
    });
});

// Rotas de Autenticação (sem rotas de registro)
Auth::routes(['register' => false]);

// Rotas disponíveis apenas para usuários autenticados
Route::middleware('auth')->group(function () {
    // Rota para a página inicial após login
    Route::get('/home', [HomeController::class, 'index'])->name('cadastro');

    // Rota para processar o cadastro de contas de jogos
    Route::post('/cadastrar-conta', [ContaController::class, 'cadastrar']);

    // Rota para buscar jogos com base nos caracteres de entrada
    Route::get('/buscar-jogos', [ContaController::class, 'buscarJogos']);

    // Rota para pesquisar contas
    Route::post('/pesquisar-contas', [ContaController::class, 'pesquisarContas']);

    // Rota para listar jogos
    Route::get('/listar-jogos', [ContaController::class, 'listarJogos']);

    // Rota para atualizar uma conta
    Route::post('/atualizar-conta', [ContaController::class, 'atualizarConta']);

    // Rota para excluir uma conta
    Route::delete('/excluir-conta/{id}', [ContaController::class, 'excluirConta']);
});

// Rota de fallback para páginas não encontradas
Route::fallback(function () {
    return response()->view('404', ['message' => 'Página não encontrada.']);
});
