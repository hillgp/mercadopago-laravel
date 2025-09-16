<?php

namespace HillPires\LaravelMercadoPago\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use HillPires\LaravelMercadoPago\Models\Configuracao;

/**
 * Controller para gerenciamento de configurações do Mercado Pago.
 *
 * Fornece operações CRUD completas e teste de conexão.
 */
class ConfiguracaoController extends Controller
{
    /**
     * Exibe uma listagem das configurações.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $config = Configuracao::ativa();

        $configuracoes = [
            'sandbox' => ($config->modo ?? 'sandbox') === 'sandbox',
            'access_token' => $config->access_token ?? '',
            'public_key' => $config->public_key ?? '',
            'client_id' => $config->client_id ?? '',
            'client_secret' => $config->client_secret ?? '',
            'webhook_url' => $config->webhook_url ?? '',
            'notification_url' => $config->notification_url ?? '',
            'success_url' => $config->success_url ?? '',
            'failure_url' => $config->failure_url ?? '',
            'pending_url' => $config->pending_url ?? '',
            'currency' => $config->currency ?? 'BRL',
            'country' => $config->country ?? 'BR',
        ];

        return view('laravel-mercadopago::configuracoes.index', compact('configuracoes'));
    }

    /**
     * Mostra o formulário para criação de uma nova configuração.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('laravel-mercadopago::configuracoes.create');
    }

    /**
     * Armazena uma nova configuração no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'access_token' => 'required|string|max:255',
            'public_key' => 'required|string|max:255',
            'client_id' => 'nullable|string|max:255',
            'client_secret' => 'nullable|string|max:255',
            'modo' => 'nullable|in:sandbox,production',
            'enable_pix' => 'boolean',
            'pix_expires_in' => 'integer|min:60|max:86400',
            'enable_cards' => 'boolean',
            'max_installments' => 'integer|min:1|max:24',
            'free_installments' => 'integer|min:1|max:12',
            'enable_boleto' => 'boolean',
            'boleto_expires_in' => 'integer|min:3600|max:2592000',
            'enable_transfers' => 'boolean',
            'transfer_daily_limit' => 'integer|min:0',
            'currency' => 'nullable|string|max:3',
            'country' => 'nullable|string|max:2',
            'webhook_url' => 'nullable|url|max:500',
            'notification_url' => 'nullable|url|max:500',
            'success_url' => 'nullable|url|max:500',
            'failure_url' => 'nullable|url|max:500',
            'pending_url' => 'nullable|url|max:500',
        ]);

        $dados = $request->all();
        // Mapear checkbox sandbox para modo
        $dados['modo'] = $request->boolean('sandbox') ? 'sandbox' : ($request->input('modo') ?: 'production');

        $configuracao = Configuracao::updateOrCreate(['id' => 1], $dados);

        // Atualizar configuração global se for a ativa
        if ($configuracao->id == 1) {
            config(['mercadopago.access_token' => $configuracao->access_token]);
            config(['mercadopago.public_key' => $configuracao->public_key]);
            config(['mercadopago.modo' => $configuracao->modo]);
        }

        return redirect()->route('configuracoes.index')
                        ->with('success', 'Configuração criada com sucesso!');
    }

    /**
     * Exibe uma configuração específica.
     *
     * @param  \HillPires\LaravelMercadoPago\Models\Configuracao  $configuracao
     * @return \Illuminate\View\View
     */
    public function show(Configuracao $configuracao)
    {
        return view('laravel-mercadopago::configuracoes.show', compact('configuracao'));
    }

    /**
     * Mostra o formulário para edição de uma configuração.
     *
     * @param  \HillPires\LaravelMercadoPago\Models\Configuracao  $configuracao
     * @return \Illuminate\View\View
     */
    public function edit(Configuracao $configuracao)
    {
        return view('laravel-mercadopago::configuracoes.edit', compact('configuracao'));
    }

    /**
     * Atualiza uma configuração no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \HillPires\LaravelMercadoPago\Models\Configuracao  $configuracao
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Configuracao $configuracao)
    {
        $request->validate([
            'access_token' => 'required|string|max:255',
            'public_key' => 'required|string|max:255',
            'client_id' => 'nullable|string|max:255',
            'client_secret' => 'nullable|string|max:255',
            'modo' => 'required|in:sandbox,production',
            'enable_pix' => 'boolean',
            'pix_expires_in' => 'integer|min:60|max:86400',
            'enable_cards' => 'boolean',
            'max_installments' => 'integer|min:1|max:24',
            'free_installments' => 'integer|min:1|max:12',
            'enable_boleto' => 'boolean',
            'boleto_expires_in' => 'integer|min:3600|max:2592000',
            'enable_transfers' => 'boolean',
            'transfer_daily_limit' => 'integer|min:0',
            'currency' => 'nullable|string|max:3',
            'country' => 'nullable|string|max:2',
            'webhook_url' => 'nullable|url|max:500',
            'notification_url' => 'nullable|url|max:500',
            'success_url' => 'nullable|url|max:500',
            'failure_url' => 'nullable|url|max:500',
            'pending_url' => 'nullable|url|max:500',
        ]);

        $configuracao->update($request->all());

        // Atualizar configuração global se for a ativa
        if ($configuracao->id == 1) {
            config(['mercadopago.access_token' => $configuracao->access_token]);
            config(['mercadopago.public_key' => $configuracao->public_key]);
            config(['mercadopago.modo' => $configuracao->modo]);
        }

        return redirect()->route('configuracoes.index')
                        ->with('success', 'Configuração atualizada com sucesso!');
    }

    /**
     * Remove uma configuração do banco de dados.
     *
     * @param  \HillPires\LaravelMercadoPago\Models\Configuracao  $configuracao
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Configuracao $configuracao)
    {
        $configuracao->delete();

        return redirect()->route('configuracoes.index')
                        ->with('success', 'Configuração deletada com sucesso!');
    }

    /**
     * Testa a conexão com o Mercado Pago para uma configuração.
     *
     * @param  \HillPires\LaravelMercadoPago\Models\Configuracao  $configuracao
     * @return \Illuminate\Http\JsonResponse
     */
    public function testarConexao(Request $request)
    {
        $accessToken = $request->input('access_token');
        if (!$accessToken) {
            return response()->json(['success' => false, 'message' => 'Access Token não informado'], 422);
        }

        // Testar endpoint público simples: payment_methods
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.mercadopago.com/v1/payment_methods');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $responseBody = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr = curl_error($ch);
        curl_close($ch);

        if ($curlErr) {
            return response()->json(['success' => false, 'message' => 'Erro de rede ao conectar ao Mercado Pago'], 500);
        }

        $json = json_decode($responseBody, true);

        if ($httpCode >= 200 && $httpCode < 300 && is_array($json)) {
            return response()->json([
                'success' => true,
                'data' => [
                    'methods_count' => count($json),
                    'http_code' => $httpCode,
                ],
            ]);
        }

        $message = isset($json['message']) ? $json['message'] : 'Falha na conexão. Verifique as credenciais.';
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => ['http_code' => $httpCode],
        ], 400);
    }
}