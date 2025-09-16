<?php

namespace HillPires\LaravelMercadoPago;

use Illuminate\Support\ServiceProvider;
use MercadoPago\MercadoPagoConfig;

/**
 * ServiceProvider para o pacote Laravel Mercado Pago.
 * Registra configurações, rotas, views e publicações do pacote.
 */
class MercadoPagoServiceProvider extends ServiceProvider
{
    /**
     * Indica se o provider deve registrar seus serviços.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Nome do pacote.
     *
     * @var string
     */
    protected $packageName = 'laravel-mercadopago';

    /**
     * Bootstrap o aplicativo.
     *
     * @return void
     */
    public function boot()
    {
        // Publicar configurações
        $this->publishes([
            __DIR__ . '/../config/mercadopago.php' => config_path('mercadopago.php'),
        ], 'config');

        // Publicar migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'migrations');

        // Publicar views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/laravel-mercadopago'),
        ], 'views');

        // Carregar views do pacote
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-mercadopago');

        // Carregar rotas do pacote
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Inicializar SDK do Mercado Pago com configurações
        $this->app->booted(function () {
            $config = config('mercadopago');
            if ($config['access_token'] && $config['public_key']) {
                MercadoPagoConfig::setAccessToken($config['access_token']);
            }
        });
    }

    /**
     * Registrar serviços no container.
     *
     * @return void
     */
    public function register()
    {
        // Mergear configurações padrão
        $this->mergeConfigFrom(__DIR__ . '/../config/mercadopago.php', 'mercadopago');

        // Nenhum bind necessário; o SDK v2 usa MercadoPagoConfig::setAccessToken
    }
}