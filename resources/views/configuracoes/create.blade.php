@extends('layouts.app')

@section('title', 'Nova Configuração Mercado Pago')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Nova Configuração do Mercado Pago</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('configuracoes.store') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="access_token" class="form-label">Access Token</label>
                            <input type="text" class="form-control @error('access_token') is-invalid @enderror" id="access_token" name="access_token" value="{{ old('access_token') }}" required>
                            @error('access_token')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="public_key" class="form-label">Public Key</label>
                            <input type="text" class="form-control @error('public_key') is-invalid @enderror" id="public_key" name="public_key" value="{{ old('public_key') }}" required>
                            @error('public_key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="modo" class="form-label">Modo de Operação</label>
                            <select class="form-control @error('modo') is-invalid @enderror" id="modo" name="modo" required>
                                <option value="">Selecione</option>
                                <option value="sandbox" {{ old('modo') == 'sandbox' ? 'selected' : '' }}>Sandbox (Testes)</option>
                                <option value="production" {{ old('modo') == 'production' ? 'selected' : '' }}>Produção</option>
                            </select>
                            @error('modo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">
                                <input type="checkbox" name="enable_pix" value="1" {{ old('enable_pix') ? 'checked' : '' }} id="enable_pix">
                                Habilitar Pagamentos via PIX
                            </label>
                            @error('enable_pix')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="pix_expires_in" class="form-label">Tempo de Expiração do QR Code PIX (segundos)</label>
                            <input type="number" class="form-control @error('pix_expires_in') is-invalid @enderror" id="pix_expires_in" name="pix_expires_in" value="{{ old('pix_expires_in', 1800) }}" min="60" max="86400">
                            <small class="form-text text-muted">Default: 1800 segundos (30 minutos). Máximo: 86400 (24 horas).</small>
                            @error('pix_expires_in')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">
                                <input type="checkbox" name="enable_cards" value="1" {{ old('enable_cards') ? 'checked' : '' }} id="enable_cards">
                                Habilitar Pagamentos via Cartão de Crédito
                            </label>
                            @error('enable_cards')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="max_installments" class="form-label">Máximo de Parcelas Permitidas</label>
                            <input type="number" class="form-control @error('max_installments') is-invalid @enderror" id="max_installments" name="max_installments" value="{{ old('max_installments', 12) }}" min="1" max="24">
                            <small class="form-text text-muted">Default: 12. Máximo: 24.</small>
                            @error('max_installments')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="free_installments" class="form-label">Número de Parcelas Sem Juros</label>
                            <input type="number" class="form-control @error('free_installments') is-invalid @enderror" id="free_installments" name="free_installments" value="{{ old('free_installments', 1) }}" min="1" max="12">
                            <small class="form-text text-muted">Default: 1. Máximo: 12.</small>
                            @error('free_installments')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">
                                <input type="checkbox" name="enable_boleto" value="1" {{ old('enable_boleto') ? 'checked' : '' }} id="enable_boleto">
                                Habilitar Pagamentos via Boleto Bancário
                            </label>
                            @error('enable_boleto')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="boleto_expires_in" class="form-label">Tempo de Expiração do Boleto (segundos)</label>
                            <input type="number" class="form-control @error('boleto_expires_in') is-invalid @enderror" id="boleto_expires_in" name="boleto_expires_in" value="{{ old('boleto_expires_in', 259200) }}" min="3600" max="2592000">
                            <small class="form-text text-muted">Default: 259200 segundos (3 dias). Máximo: 2592000 (30 dias).</small>
                            @error('boleto_expires_in')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">
                                <input type="checkbox" name="enable_transfers" value="1" {{ old('enable_transfers') ? 'checked' : '' }} id="enable_transfers">
                                Habilitar Transferências via Mercado Pago
                            </label>
                            @error('enable_transfers')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="transfer_daily_limit" class="form-label">Limite Diário de Transferências (R$)</label>
                            <input type="number" class="form-control @error('transfer_daily_limit') is-invalid @enderror" id="transfer_daily_limit" name="transfer_daily_limit" value="{{ old('transfer_daily_limit', 0) }}" min="0" step="0.01">
                            <small class="form-text text-muted">Default: 0 (sem limite). Valor em reais.</small>
                            @error('transfer_daily_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Criar Configuração</button>
                            <a href="{{ route('configuracoes.index') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection