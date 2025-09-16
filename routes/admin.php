<?php

use Illuminate\Support\Facades\Route;
use HillPires\LaravelMercadoPago\Http\Controllers\ConfiguracaoController;

/*
|--------------------------------------------------------------------------
| Rotas Administrativas do Mercado Pago
|--------------------------------------------------------------------------
|
| Aqui são definidas as rotas para o painel administrativo do Mercado Pago.
| Todas as rotas usam o prefixo 'admin' e namespace apropriado.
|
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/mercadopago', [ConfiguracaoController::class, 'index'])->name('mercadopago.index');
    Route::post('/mercadopago', [ConfiguracaoController::class, 'store'])->name('mercadopago.salvar');
    Route::post('/mercadopago/testar-conexao', [ConfiguracaoController::class, 'testarConexao'])->name('mercadopago.testar-conexao');
});