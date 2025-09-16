@extends('layouts.app')

@section('title', 'Visualizar Configuração Mercado Pago')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Configuração do Mercado Pago #{{ $configuracao->id }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>ID:</strong> {{ $configuracao->id }}</p>
                            <p><strong>Access Token:</strong> {{ Str::mask(Str::limit($configuracao->access_token, 20, ''), '*', 0, 10) }}...</p>
                            <p><strong>Public Key:</strong> {{ Str::mask(Str::limit($configuracao->public_key, 20, ''), '*', 0, 10) }}...</p>
                            <p><strong>Modo:</strong>
                                <span class="badge badge-{{ $configuracao->modo == 'sandbox' ? 'warning' : 'success' }}">
                                    {{ $configuracao->modo == 'sandbox' ? 'Sandbox' : 'Produção' }}
                                </span>
                            </p>
                            <p><strong>PIX Habilitado:</strong>
                                <span class="badge badge-{{ $configuracao->enable_pix ? 'success' : 'secondary' }}">
                                    {{ $configuracao->enable_pix ? 'Sim' : 'Não' }}
                                </span>
                            </p>
                            <p><strong>Expiração PIX:</strong> {{ $configuracao->pix_expires_in }} segundos ({{ gmdate('H:i:s', $configuracao->pix_expires_in) }})</p>
                            <p><strong>Cartão Habilitado:</strong>
                                <span class="badge badge-{{ $configuracao->enable_cards ? 'success' : 'secondary' }}">
                                    {{ $configuracao->enable_cards ? 'Sim' : 'Não' }}
                                </span>
                            </p>
                            <p><strong>Máximo de Parcelas:</strong> {{ $configuracao->max_installments }}</p>
                            <p><strong>Parcelas Sem Juros:</strong> {{ $configuracao->free_installments }}</p>
                            <p><strong>Boleto Habilitado:</strong>
                                <span class="badge badge-{{ $configuracao->enable_boleto ? 'success' : 'secondary' }}">
                                    {{ $configuracao->enable_boleto ? 'Sim' : 'Não' }}
                                </span>
                            </p>
                            <p><strong>Expiração Boleto:</strong> {{ $configuracao->boleto_expires_in }} segundos ({{ gmdate('H:i:s', $configuracao->boleto_expires_in) }})</p>
                            <p><strong>Criado em:</strong> {{ $configuracao->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Atualizado em:</strong> {{ $configuracao->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <a href="{{ route('configuracoes.edit', $configuracao) }}" class="btn btn-warning">Editar</a>
                        <button class="btn btn-success teste-conexao" data-id="{{ $configuracao->id }}">Testar Conexão</button>
                        <form action="{{ route('configuracoes.destroy', $configuracao) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja deletar esta configuração?')">Deletar</button>
                        </form>
                        <a href="{{ route('configuracoes.index') }}" class="btn btn-secondary">Voltar à Lista</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const button = document.querySelector('.teste-conexao');
    if (button) {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch(`/configuracoes/${id}/teste`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
            })
            .catch(error => {
                alert('Erro ao testar conexão.');
            });
        });
    }
});
</script>
@endsection