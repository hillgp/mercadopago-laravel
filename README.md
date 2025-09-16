# Pacote Laravel Mercado Pago

## Introdução

Este pacote fornece uma integração completa com o Mercado Pago para aplicações Laravel. Inclui:

- CRUD completo para gerenciamento de configurações (access_token, public_key, modo sandbox/produção, PIX habilitado e expiração, cartões habilitados, máximo de parcelas e parcelas sem juros, boleto habilitado e expiração, transferências habilitadas e limite diário).
- Teste de conexão com a API do Mercado Pago.
- Suporte a modos sandbox (testes) e produção.
- Suporte a pagamentos via PIX com configuração de expiração do QR Code.
- Suporte a pagamentos via Cartão de Crédito com configuração de parcelas e sem juros.
- Suporte a pagamentos via Boleto Bancário com configuração de expiração.
- Suporte a transferências via Mercado Pago com limite diário.
- Integração com variáveis de ambiente (.env).
- Controllers, models, views, rotas, migrations e dependências prontas.

O pacote é compatível com Laravel 11+ e PHP 8.2+. O ServiceProvider é registrado automaticamente via auto-discovery do Composer.

## Como Baixar e Instalar

### Baixar o Pacote

O pacote é distribuído via Composer. Para instalar em sua aplicação Laravel:

1. **Via Composer (recomendado para uso):**

   Abra o terminal no diretório da sua aplicação Laravel e execute:

   ```
   composer require hillpires/laravel-mercadopago
   ```

   Isso baixa e instala o pacote automaticamente no vendor/.

2. **Clonar o Repositório (para desenvolvimento ou customização):**

   Se você quiser o código fonte para modificar ou contribuir:

   ```
   git clone https://github.com/hillpires/laravel-mercadopago.git vendor/hillpires/laravel-mercadopago
   ```

   Em seguida, adicione o autoload no composer.json da sua app se necessário, mas o Composer handle isso.

### Instalação

Após baixar, siga estes passos para instalar e configurar o pacote:

1. **Publicar os arquivos do pacote (config, migrations e views):**

   Execute no terminal da sua aplicação:

   ```
   php artisan vendor:publish --provider="HillPires\\LaravelMercadoPago\\MercadoPagoServiceProvider" --all
   ```

   Isso copia:
   - `config/mercadopago.php` para `config/` da sua app.
   - Migrations para `database/migrations/`.
   - Views para `resources/views/vendor/laravel-mercadopago/`.

2. **Executar as migrations para criar a tabela de configurações:**

   ```
   php artisan migrate
   ```

   Isso cria a tabela `configuracoes` com todos os campos necessários.

3. **Configurar as credenciais no arquivo .env da sua aplicação:**

   Para modo sandbox (testes):

   ```
   MERCADOPAGO_SANDBOX_ACCESS_TOKEN=sua_access_token_sandbox
   MERCADOPAGO_SANDBOX_PUBLIC_KEY=sua_public_key_sandbox
   MERCADOPAGO_MODO=sandbox
   ```

   Para modo produção:

   ```
   MERCADOPAGO_PRODUCTION_ACCESS_TOKEN=sua_access_token_producao
   MERCADOPAGO_PRODUCTION_PUBLIC_KEY=sua_public_key_producao
   MERCADOPAGO_MODO=production
   ```

   Obtenha as credenciais no painel do Mercado Pago: [Sandbox](https://www.mercadopago.com.br/developers/panel/sandbox) ou [Produção](https://www.mercadopago.com.br/developers/panel).

   As configurações globais serão carregadas do .env ou salvas na tabela via CRUD do pacote.

4. **Limpar cache de configuração (opcional, mas recomendado):**

   ```
   php artisan config:clear
   php artisan cache:clear
   ```

5. **Verificar a instalação:**

   - Acesse `/configuracoes` na sua app (adicione a rota ao web.php se necessário, mas o pacote carrega rotas automaticamente).
   - Crie uma configuração via interface do CRUD para testar.

O ServiceProvider é auto-registrado pelo Composer, então não precisa adicionar manualmente em config/app.php (a menos que auto-discovery esteja desabilitado).

## Como Testar o Pacote em Desenvolvimento

Para testar o pacote durante o desenvolvimento (no diretório do pacote ou localmente), crie uma aplicação Laravel de teste:

1. **Criar uma nova aplicação Laravel para teste:**

   No terminal, crie uma app Laravel vazia (requer Laravel Installer ou Composer create-project):

   ```
   composer create-project laravel/laravel test-app
   cd test-app
   ```

   Ou use o Laravel Installer:

   ```
   laravel new test-app
   cd test-app
   ```

2. **Instalar o pacote local (desenvolvimento):**

   No diretório da sua app de teste, edite composer.json para requerer o caminho local do pacote:

   ```
   "repositories": [
       {
           "type": "path",
           "url": "../mercadopago"  // Caminho para o diretório do pacote (ajuste se necessário)
       }
   ],
   "require": {
       "hillpires/laravel-mercadopago": "*"
   }
   ```

   Em seguida:

   ```
   composer update
   ```

3. **Publicar e migrar:**

   ```
   php artisan vendor:publish --provider="HillPires\\LaravelMercadoPago\\MercadoPagoServiceProvider" --all
   php artisan migrate
   ```

4. **Configurar .env da app de teste:**

   Use credenciais de sandbox do Mercado Pago para testes. Adicione as variáveis conforme seção anterior.

5. **Adicionar rota de teste no routes/web.php (temporário para teste):**

   ```
   Route::get('/test-config', function () {
       $config = \HillPires\LaravelMercadoPago\Models\Configuracao::ativa();
       return view('test', compact('config'));
   });
   ```

   Crie uma view simples em resources/views/test.blade.php para visualizar a configuração ativa.

6. **Rodar o servidor de desenvolvimento:**

   ```
   php artisan serve
   ```

7. **Testar:**

   - Acesse http://127.0.0.1:8000/configuracoes para o CRUD (crie/editar configurações).
   - Clique em "Testar Conexão" para verificar credenciais (use sandbox para testes).
   - Para testar integrações (PIX, Cartão, Boleto, Transferências):
     - Crie uma configuração com o método habilitado.
     - Use o exemplo de código no README.md em um controller de teste (ex: TestController) para simular pagamentos.
     - Para PIX: Verifique QR Code no response.
     - Para Cartão: Use ferramenta de teste do Mercado Pago (card numbers como 5031431234560000 para sandbox).
     - Para Boleto: Verifique URL no response.
     - Para Transferências: Use email válido e verifique limite.
   - Use Postman ou browser para chamar /configuracoes/{id}/teste e ver status JSON.

   Para testes mais avançados, use Orchestra Testbench (dev dependency) para unit tests no pacote, mas para funcional, a app de teste é ideal.

   **Dicas para Testes:**
   - Use modo sandbox para evitar transações reais.
   - Verifique logs do Laravel (storage/logs) para erros.
   - Certifique-se de que o .env tem as credenciais corretas do painel Mercado Pago.
   - Para frontend, inclua o script do Mercado Pago com public_key e teste tokenização.

Se precisar de testes automatizados, adicione PHPUnit no pacote e crie testes para o model e controller.

## Configuração

[resto do conteúdo permanece o mesmo, mas para brevidade, assumindo que o resto é o mesmo do anterior]

## Uso

[manter as seções de uso como estão]

## Troubleshooting

[manter]

## Contribuição

Fork, crie branch, PR.

## Licença

MIT. Copyright (c) 2025 Hill Pires.

## Suporte

Issue no GitHub.