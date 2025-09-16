<?php

namespace HillPires\LaravelMercadoPago\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model para Configuração do Mercado Pago.
 *
 * Representa as configurações de credenciais e modo de operação no banco de dados.
 */
class Configuracao extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao model.
     *
     * @var string
     */
    protected $table = 'configuracoes';

    /**
     * Os atributos que são mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'access_token',
        'public_key',
        'client_id',
        'client_secret',
        'modo',
        'enable_pix',
        'pix_expires_in',
        'enable_cards',
        'max_installments',
        'free_installments',
        'enable_boleto',
        'boleto_expires_in',
        'enable_transfers',
        'transfer_daily_limit',
        'currency',
        'country',
        'webhook_url',
        'notification_url',
        'success_url',
        'failure_url',
        'pending_url',
    ];

    /**
     * Os atributos que devem ser casted para tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'modo' => 'string',
        'enable_pix' => 'boolean',
        'pix_expires_in' => 'integer',
        'enable_cards' => 'boolean',
        'max_installments' => 'integer',
        'free_installments' => 'integer',
        'enable_boleto' => 'boolean',
        'boleto_expires_in' => 'integer',
        'enable_transfers' => 'boolean',
        'transfer_daily_limit' => 'integer',
    ];

    /**
     * Obtém a configuração ativa (primeira ou id=1).
     *
     * @return static|null
     */
    public static function ativa()
    {
        return self::firstOrCreate(['id' => 1]);
    }

    /**
     * Testa a conexão com o Mercado Pago usando as credenciais.
     *
     * @return bool
     */
    public function testarConexao()
    {
        try {
            if (!$this->access_token || !$this->public_key) {
                return false;
            }

            // Configurar SDK temporariamente para teste
            \MercadoPago\MercadoPagoConfig::setAccessToken($this->access_token);

            // Fazer uma chamada simples para testar, como obter o usuário
            $user = \MercadoPago\Client\User::get();

            return $user !== null;
        } catch (\Exception $e) {
            return false;
        }
    }
}