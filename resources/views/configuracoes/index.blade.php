@extends('layouts.app')

@section('title', 'Configurações Mercado Pago')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Configurações do Mercado Pago
                    <a href="{{ route('configuracoes.create') }}" class="btn btn-primary float-right">Nova Configuração</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($configuracoes->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Access Token</th>
                                    <th>Public Key</th>
                                    <th>Modo</th>
                                    <th>PIX Habilitado</th>
                                    <th>Expiração PIX (s)</th>
                                    <th>Cartão Habilitado</th>
                                    <th>Max Parcelas</th>
                                    <th>Parcelas Sem Juros</th>
                                    <th>Boleto Habilitado</th>
                                    <th>Expiração Boleto (s)</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($configuracoes as $configuracao)
                                    <tr>
                                        <td>{{ $configuracao->id }}</td>
                                        <td>{{ Str::limit($configuracao->access_token, 20) }}...</td>
                                        <td>{{ Str::limit($configuracao->public_key, 20) }}...</td>
                                        <td>
                                            <span class="badge badge-{{ $configuracao->modo == 'sandbox' ? 'warning' : 'success' }}">
                                                {{ $configuracao->modo == 'sandbox' ? 'Sandbox' : 'Produção' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $configuracao->enable_pix ? 'success' : 'secondary' }}">
                                                {{ $configuracao->enable_pix ? 'Sim' : 'Não' }}
                                            </span>
                                        </td>
                                        <td>{{ $configuracao->pix_expires_in }}s ({{ gmdate('H:i:s', $configuracao->pix_expires_in) }})</td>
                                        <td>
                                            <span class="badge badge-{{ $configuracao->enable_cards ? 'success' : 'secondary' }}">
                                                {{ $configuracao->enable_cards ? 'Sim' : 'Não' }}
                                            </span>
                                        </td>
                                        <td>{{ $configuracao->max_installments }}</td>
                                        <td>{{ $configuracao->free_installments }}</td>
                                        <td>
                                            <span class="badge badge-{{ $configuracao->enable_boleto ? 'success' : 'secondary' }}">
                                                {{ $configuracao->enable_boleto ? 'Sim' : 'Não' }}
                                            </span>
                                        </td>
                                        <td>{{ $configuracao->boleto_expires_in }}s ({{ gmdate('H:i:s', $configuracao->boleto_expires_in) }})</td>
                                        <td>
                                            <a href="{{ route('configuracoes.show', $configuracao) }}" class="btn btn-info btn-sm">Visualizar</a>
                                            <a href="{{ route('configuracoes.edit', $configuracao) }}" class="btn btn-warning btn-sm">Editar</a>
                                            <button class="btn btn-success btn-sm teste-conexao" data-id="{{ $configuracao->id }}">Testar Conexão</button>
                                            <form action="{{ route('configuracoes.destroy', $configuracao) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza?')">Deletar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Nenhuma configuração encontrada. <a href="{{ route('configuracoes.create') }}">Criar a primeira</a>.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.teste-conexao').forEach(button => {
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
    });
});
</script>
@endsection