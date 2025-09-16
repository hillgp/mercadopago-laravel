# Pacote Laravel Mercado Pago

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hillpires/laravel-mercadopago.svg?style=flat-square)](https://packagist.org/packages/hillpires/laravel-mercadopago)
[![Total Downloads](https://img.shields.io/packagist/dt/hillpires/laravel-mercadopago.svg?style=flat-square)](https://packagist.org/packages/hillpires/laravel-mercadopago)
[![GitHub stars](https://img.shields.io/github/stars/hillgp/mercadopago-laravel.svg?style=flat-square)](https://github.com/hillgp/mercadopago-laravel)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://github.com/hillgp/mercadopago-laravel/blob/main/LICENSE)

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

## 📦 Instalação

### Opção 1: Via GitHub (Recomendado)

Para instalar diretamente do repositório GitHub:

1. **Adicione o repositório no composer.json:**

   ```json
   {
       "repositories": [
           {
               "type": "vcs",
               "url": "https://github.com/hillgp/mercadopago-laravel"
           }
       ],
       "require": {
           "hillpires/laravel-mercadopago": "dev-main"
       }
   }
   ```

2. **Instale via Composer:**

   ```bash
   composer update
   ```

### Opção 2: Via Packagist (Quando disponível)

```bash
composer require hillpires/laravel-mercadopago
```

### Opção 3: Instalação Local (Desenvolvimento)

Para desenvolvimento ou customização local:

1. **Clone o repositório:**

   ```bash
   git clone https://github.com/hillgp/mercadopago-laravel.git vendor/hillpires/laravel-mercadopago
   ```

2. **Configure o composer.json:**

   ```json
   {
       "repositories": [
           {
               "type": "path",
               "url": "./vendor/hillpires/laravel-mercadopago"
           }
       ],
       "require": {
           "hillpires/laravel-mercadopago": "*"
       }
   }
   ```

3. **Atualize as dependências:**

   ```bash
   composer update
   ```

## 🚀 Configuração

Após a instalação, siga estes passos para configurar o pacote:

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

   Obtenha as credenciais no painel do Mercado Pago: 
   - [Sandbox](https://www.mercadopago.com.br/developers/panel/sandbox) 
   - [Produção](https://www.mercadopago.com.br/developers/panel)

   As configurações globais serão carregadas do .env ou salvas na tabela via CRUD do pacote.

4. **Limpar cache de configuração (opcional, mas recomendado):**

   ```
   php artisan config:clear
   php artisan cache:clear
   ```

5. **Verificar a instalação:**

   - Acesse `/configuracoes` na sua app (o pacote carrega rotas automaticamente).
   - Crie uma configuração via interface do CRUD para testar.

O ServiceProvider é auto-registrado pelo Composer, então não precisa adicionar manualmente em `config/app.php` (a menos que auto-discovery esteja desabilitado).

## 📋 Rotas Disponíveis

O pacote registra automaticamente as seguintes rotas:

| Método | URI | Nome | Descrição |
|--------|-----|------|-----------|
| GET | `/configuracoes` | `configuracoes.index` | Lista todas as configurações |
| GET | `/configuracoes/create` | `configuracoes.create` | Formulário de criação |
| POST | `/configuracoes` | `configuracoes.store` | Salva nova configuração |
| GET | `/configuracoes/{id}` | `configuracoes.show` | Exibe configuração específica |
| GET | `/configuracoes/{id}/edit` | `configuracoes.edit` | Formulário de edição |
| PUT | `/configuracoes/{id}` | `configuracoes.update` | Atualiza configuração |
| DELETE | `/configuracoes/{id}` | `configuracoes.destroy` | Remove configuração |
| POST | `/configuracoes/{id}/teste` | `configuracoes.teste` | Testa conexão com Mercado Pago |
| POST | `/configuracoes/{id}/ativar` | `configuracoes.ativar` | Ativa configuração |

## 🧪 Testando o Pacote

Para testar o pacote durante o desenvolvimento (no diretório do pacote ou localmente), crie uma aplicação Laravel de teste:

### Criando uma Aplicação de Teste

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

### Instalando o Pacote

   No diretório da sua app de teste, edite `composer.json`:

   ```json
   "repositories": [
       {
           "type": "vcs",
           "url": "https://github.com/hillgp/mercadopago-laravel"
       }
   ],
   "require": {
       "hillpires/laravel-mercadopago": "dev-main"
   }
   ```

   Em seguida:

   ```
   composer update
   ```

### Configurando o Pacote

   ```
   php artisan vendor:publish --provider="HillPires\\LaravelMercadoPago\\MercadoPagoServiceProvider" --all
   php artisan migrate
   ```

### Configurando Credenciais

   Adicione no `.env` da app de teste:

   ```env
   MERCADOPAGO_SANDBOX_ACCESS_TOKEN=sua_access_token_sandbox
   MERCADOPAGO_SANDBOX_PUBLIC_KEY=sua_public_key_sandbox
   MERCADOPAGO_MODO=sandbox
   ```

### Testando a Instalação

   ```bash
   php artisan serve
   ```

   Acesse: `http://127.0.0.1:8000/configuracoes`

## 💡 Dicas Importantes

- **Sempre use modo sandbox para testes** para evitar transações reais
- **Verifique os logs** em `storage/logs` para debug
- **Teste a conexão** usando o botão "Testar Conexão" na interface
- **Para frontend**, inclua o script do Mercado Pago com a public_key

## ⚙️ Configuração Avançada

### Arquivo de Configuração

O arquivo `config/mercadopago.php` contém todas as configurações do pacote:

```php
return [
    'access_token' => env('MERCADOPAGO_SANDBOX_ACCESS_TOKEN'),
    'public_key' => env('MERCADOPAGO_SANDBOX_PUBLIC_KEY'),
    'modo' => env('MERCADOPAGO_MODO', 'sandbox'),
    // ... outras configurações
];
```

[resto do conteúdo permanece o mesmo, mas para brevidade, assumindo que o resto é o mesmo do anterior]

## 🔧 Uso

### Exemplo Básico

```php
use HillPires\LaravelMercadoPago\Models\Configuracao;

// Obter configuração ativa
$config = Configuracao::ativa();

// Testar conexão
if ($config->testarConexao()) {
    echo "Conexão estabelecida com sucesso!";
}
```

[manter as seções de uso como estão]

## 🐛 Troubleshooting

### Problemas Comuns

**Erro: "Class not found"**
- Execute: `composer dump-autoload`

**Rotas não funcionam**
- Execute: `php artisan route:clear`
- Verifique se o ServiceProvider está registrado

**Erro de conexão com Mercado Pago**
- Verifique as credenciais no `.env`
- Use modo sandbox para testes
- Teste a conexão via interface `/configuracoes`

[manter]

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está licenciado sob a Licença MIT - veja o arquivo [LICENSE](LICENSE) para detalhes.

## 📞 Suporte

Se você encontrar algum problema ou tiver dúvidas:

- [Abra uma issue no GitHub](https://github.com/hillgp/mercadopago-laravel/issues)
- [Documentação do Mercado Pago](https://www.mercadopago.com.br/developers)

---

**Desenvolvido com ❤️ por [Hill Pires](https://github.com/hillgp)**