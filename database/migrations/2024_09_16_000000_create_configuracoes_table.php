<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration para criar a tabela de configurações do Mercado Pago.
 *
 * Esta tabela armazena as credenciais e configurações para integração com o Mercado Pago.
 */
return new class extends Migration
{
    /**
     * Executa a migration.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuracoes', function (Blueprint $table) {
            $table->id();

            $table->string('access_token')->nullable()->comment('Token de acesso para API do Mercado Pago');
            $table->string('public_key')->nullable()->comment('Chave pública para frontend do Mercado Pago');
            $table->string('client_id')->nullable()->comment('Client ID para OAuth');
            $table->string('client_secret')->nullable()->comment('Client Secret para OAuth');
            $table->enum('modo', ['sandbox', 'production'])->default('sandbox')->comment('Modo de operação: sandbox para testes, production para produção');

            // Campos PIX
            $table->boolean('enable_pix')->default(false)->comment('Habilitar pagamentos via PIX');
            $table->integer('pix_expires_in')->default(1800)->comment('Tempo de expiração do QR Code PIX em segundos (default: 30 minutos)');

            // Campos Cartão
            $table->boolean('enable_cards')->default(true)->comment('Habilitar pagamentos via Cartão de Crédito');
            $table->integer('max_installments')->default(12)->comment('Máximo de parcelas permitidas (default: 12)');
            $table->integer('free_installments')->default(1)->comment('Número de parcelas sem juros (default: 1)');

            // Campos Boleto
            $table->boolean('enable_boleto')->default(false)->comment('Habilitar pagamentos via Boleto Bancário');
            $table->integer('boleto_expires_in')->default(259200)->comment('Tempo de expiração do Boleto em segundos (default: 3 dias)');

            // Campos Transferências
            $table->boolean('enable_transfers')->default(false)->comment('Habilitar transferências via Mercado Pago');
            $table->integer('transfer_daily_limit')->default(0)->comment('Limite diário de transferências em R$ (0 para sem limite)');

            // Configurações adicionais
            $table->string('currency', 3)->default('BRL')->comment('Moeda padrão (default: BRL)');
            $table->string('country', 2)->default('BR')->comment('País (default: BR)');

            $table->string('webhook_url')->nullable()->comment('URL do webhook para notificações');
            $table->string('notification_url')->nullable()->comment('URL alternativa de notificação');

            $table->string('success_url')->nullable()->comment('URL de sucesso do pagamento');
            $table->string('failure_url')->nullable()->comment('URL de falha do pagamento');
            $table->string('pending_url')->nullable()->comment('URL de pendente do pagamento');

            $table->timestamps();
        });
    }

    /**
     * Reverte a migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configuracoes');
    }
};