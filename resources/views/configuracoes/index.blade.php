@extends('layouts.admin')

@section('title', 'Configurações do MercadoPago')

@section('page-title', 'Configurações do MercadoPago')
@section('page-subtitle', 'Configure as credenciais e parâmetros do MercadoPago')

@section('content')
<div class="space-y-6">
	<div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-lg p-6 text-white">
		<div class="flex items-center justify-between">
			<div>
				<h2 class="text-2xl font-bold mb-2">MercadoPago</h2>
				<p class="text-blue-100">
					Configure as credenciais para processar pagamentos PIX, cartão e boleto
				</p>
			</div>
			<div class="text-right">
				<div class="text-sm text-blue-100 mb-1">Status da Configuração</div>
				<div class="flex items-center">
					<div id="statusIndicator" class="w-3 h-3 rounded-full bg-yellow-400 mr-2"></div>
					<span id="statusText" class="text-sm font-medium">Verificando...</span>
				</div>
			</div>
		</div>
	</div>

	<div class="bg-white rounded-lg shadow-md">
		<div class="p-6">
			<h3 class="text-lg font-semibold text-gray-900 mb-4">
				<i class="fas fa-cogs mr-2"></i>Credenciais e Configurações
			</h3>

			<form id="configForm" method="POST" action="{{ route('admin.mercadopago.salvar') }}">
				@csrf

				<div class="mb-6">
					<div class="flex items-center">
						<input type="checkbox" id="sandbox" name="sandbox" value="1"
						       {{ ($configuracoes['sandbox'] ?? true) ? 'checked' : '' }}
						       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
						<label for="sandbox" class="ml-2 text-sm font-medium text-gray-700">
							Modo Sandbox (Teste)
						</label>
					</div>
					<p class="mt-1 text-sm text-gray-500">
						Marque esta opção para usar credenciais de teste do MercadoPago
					</p>
				</div>

				<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
					<div>
						<label for="access_token" class="block text-sm font-medium text-gray-700 mb-2">
							Access Token <span class="text-red-500">*</span>
						</label>
						<input type="password" id="access_token" name="access_token"
						       value="{{ $configuracoes['access_token'] ?? '' }}"
						       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
						       placeholder="APP_USR-... ou TEST-...">
						<p class="mt-1 text-sm text-gray-500">
							Token de acesso fornecido pelo MercadoPago
						</p>
						@error('access_token')
							<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
						@enderror
					</div>

					<div>
						<label for="public_key" class="block text-sm font-medium text-gray-700 mb-2">
							Public Key
						</label>
						<input type="password" id="public_key" name="public_key"
						       value="{{ $configuracoes['public_key'] ?? '' }}"
						       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
						       placeholder="APP_USR-... ou TEST-...">
						<p class="mt-1 text-sm text-gray-500">
							Chave pública para integração com formulários
						</p>
						@error('public_key')
							<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
						@enderror
					</div>

					<div>
						<label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">
							Client ID
						</label>
						<input type="text" id="client_id" name="client_id"
						       value="{{ $configuracoes['client_id'] ?? '' }}"
						       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
						       placeholder="123456789">
						<p class="mt-1 text-sm text-gray-500">
							ID do cliente fornecido pelo MercadoPago
						</p>
						@error('client_id')
							<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
						@enderror
					</div>

					<div>
						<label for="client_secret" class="block text-sm font-medium text-gray-700 mb-2">
							Client Secret
						</label>
						<input type="password" id="client_secret" name="client_secret"
						       value="{{ $configuracoes['client_secret'] ?? '' }}"
						       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
						       placeholder="ABC123...">
						<p class="mt-1 text-sm text-gray-500">
							Chave secreta do cliente
						</p>
						@error('client_secret')
							<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
						@enderror
					</div>
				</div>

				<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
					<div>
						<label for="webhook_url" class="block text-sm font-medium text-gray-700 mb-2">
							URL do Webhook
						</label>
						<input type="url" id="webhook_url" name="webhook_url"
						       value="{{ $configuracoes['webhook_url'] ?? '' }}"
						       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
						       placeholder="https://seudominio.com/webhooks/mercadopago">
						<p class="mt-1 text-sm text-gray-500">
							URL para receber notificações de pagamento
						</p>
						@error('webhook_url')
							<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
						@enderror
					</div>

					<div>
						<label for="notification_url" class="block text-sm font-medium text-gray-700 mb-2">
							URL de Notificação
						</label>
						<input type="url" id="notification_url" name="notification_url"
						       value="{{ $configuracoes['notification_url'] ?? '' }}"
						       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
						       placeholder="https://seudominio.com/webhooks/mercadopago/notification">
						<p class="mt-1 text-sm text-gray-500">
							URL alternativa para notificações
						</p>
						@error('notification_url')
							<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
						@enderror
					</div>
				</div>

				<div class="mb-6">
					<h4 class="text-lg font-semibold text-gray-900 mb-4">
						<i class="fas fa-directions mr-2 text-green-600"></i>URLs de Retorno
					</h4>
					<p class="text-sm text-gray-600 mb-4">
						Configure as URLs para as quais os usuários serão redirecionados após o pagamento
					</p>

					<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
						<div>
							<label for="success_url" class="block text-sm font-medium text-gray-700 mb-2">
								URL de Sucesso <span class="text-green-600">*</span>
							</label>
							<input type="url" id="success_url" name="success_url"
							       value="{{ $configuracoes['success_url'] ?? '' }}"
							       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
							       placeholder="https://seudominio.com/loja/success">
							<p class="mt-1 text-sm text-gray-500">
								URL para redirecionamento quando o pagamento for aprovado
							</p>
							@error('success_url')
								<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
							@enderror
						</div>

						<div>
							<label for="failure_url" class="block text-sm font-medium text-gray-700 mb-2">
								URL de Falha <span class="text-red-600">*</span>
							</label>
							<input type="url" id="failure_url" name="failure_url"
							       value="{{ $configuracoes['failure_url'] ?? '' }}"
							       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
							       placeholder="https://seudominio.com/loja/failure">
							<p class="mt-1 text-sm text-gray-500">
								URL para redirecionamento quando o pagamento for rejeitado
							</p>
							@error('failure_url')
								<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
							@enderror
						</div>

						<div>
							<label for="pending_url" class="block text_sm font-medium text-gray-700 mb-2">
								URL de Pendente <span class="text-yellow-600">*</span>
							</label>
							<input type="url" id="pending_url" name="pending_url"
							       value="{{ $configuracoes['pending_url'] ?? '' }}"
							       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
							       placeholder="https://seudominio.com/loja/pending">
							<p class="mt-1 text-sm text-gray-500">
								URL para redirecionamento quando o pagamento estiver pendente
							</p>
							@error('pending_url')
								<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
							@enderror
						</div>
					</div>
				</div>

				<div class="bg-gray-50 rounded-lg p-4 mb-6">
					<h4 class="text-sm font-medium text-gray-900 mb-3">Informações do Sistema</h4>
					<div class="grid grid-cols-2 gap-4 text-sm">
						<div>
							<span class="text-gray-600">Moeda:</span>
							<span class="font-medium">{{ $configuracoes['currency'] ?? 'BRL' }}</span>
						</div>
						<div>
							<span class="text-gray-600">País:</span>
							<span class="font-medium">{{ $configuracoes['country'] ?? 'BR' }}</span>
						</div>
					</div>
				</div>

				<div class="flex justify-between items-center">
					<button type="button" onclick="testarConexao()"
					        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
						<i class="fas fa-plug mr-2"></i>Testar Conexão
					</button>

					<div class="flex space-x-3">
						<button type="button" onclick="window.location.reload()"
						        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
							<i class="fas fa-undo mr-2"></i>Restaurar
						</button>

						<button type="submit"
						        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
							<i class="fas fa-save mr-2"></i>Salvar Configurações
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div id="testeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
		<div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
			<div class="mt-3">
				<div class="flex items-center justify-between mb-4">
					<h3 class="text-lg font-medium text-gray-900">
						<i class="fas fa-plug mr-2"></i>Teste de Conexão
					</h3>
					<button onclick="fecharModal()" class="text-gray-400 hover:text-gray-600">
						<i class="fas fa-times"></i>
					</button>
				</div>

				<div id="testeContent">
					<div class="flex items-center">
						<div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 mr-3"></div>
						<span>Testando conexão com MercadoPago...</span>
					</div>
				</div>

				<div class="flex justify-end mt-4">
					<button onclick="fecharModal()"
					        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
						Fechar
					</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script>

document.addEventListener('DOMContentLoaded', function() {
	verificarStatus();
});

function verificarStatus() {
	const accessToken = document.getElementById('access_token').value;

	if (!accessToken) {
		atualizarStatus('error', 'Access Token não configurado');
		return;
	}

	fetch('{{ route('admin.mercadopago.testar-conexao') }}', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-CSRF-TOKEN': '{{ csrf_token() }}'
		},
		body: JSON.stringify({
			access_token: accessToken
		})
	})
	.then(response => response.json())
	.then(data => {
		if (data.success) {
			atualizarStatus('success', 'Conectado com sucesso');
		} else {
			atualizarStatus('error', data.message);
		}
	})
	.catch(error => {
		atualizarStatus('error', 'Erro ao verificar status');
	});
}

function atualizarStatus(status, message) {
	const indicator = document.getElementById('statusIndicator');
	const text = document.getElementById('statusText');

	indicator.className = 'w-3 h-3 rounded-full mr-2';

	switch(status) {
		case 'success':
			indicator.classList.add('bg-green-400');
			text.textContent = message;
			text.className = 'text-sm font-medium text-green-600';
			break;
		case 'error':
			indicator.classList.add('bg-red-400');
			text.textContent = message;
			text.className = 'text-sm font-medium text-red-600';
			break;
		default:
			indicator.classList.add('bg-yellow-400');
			text.textContent = message;
			text.className = 'text-sm font-medium text-yellow-600';
	}
}

function testarConexao() {
	const accessToken = document.getElementById('access_token').value;

	if (!accessToken) {
		alert('Por favor, informe o Access Token primeiro.');
		return;
	}

	document.getElementById('testeModal').classList.remove('hidden');

	fetch('{{ route('admin.mercadopago.testar-conexao') }}', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-CSRF-TOKEN': '{{ csrf_token() }}'
		},
		body: JSON.stringify({
			access_token: accessToken
		})
	})
	.then(response => response.json())
	.then(data => {
		const content = document.getElementById('testeContent');

		if (data.success) {
			content.innerHTML = `
				<div class="text-green-600 mb-3">
					<i class="fas fa-check-circle mr-2"></i>Conexão estabelecida com sucesso!
				</div>
				<div class="text-sm text-gray-600">
					<p><strong>Métodos de pagamento:</strong> ${data.data.methods_count}</p>
					<p><strong>Código HTTP:</strong> ${data.data.http_code}</p>
				</div>
			`;
		} else {
			content.innerHTML = `
				<div class="text-red-600 mb-3">
					<i class="fas fa-exclamation-triangle mr-2"></i>Falha na conexão
				</div>
				<div class="text-sm text-gray-600">
					<p>${data.message}</p>
				</div>
			`;
		}
	})
	.catch(error => {
		const content = document.getElementById('testeContent');
		content.innerHTML = `
			<div class="text-red-600 mb-3">
				<i class="fas fa-exclamation-triangle mr-2"></i>Erro interno
			</div>
			<div class="text-sm text-gray-600">
				<p>Ocorreu um erro inesperado. Tente novamente.</p>
			</div>
		`;
	});
}

function fecharModal() {
	document.getElementById('testeModal').classList.add('hidden');
}

document.getElementById('access_token')?.addEventListener('input', function() {
	clearTimeout(window.statusTimeout);
	window.statusTimeout = setTimeout(verificarStatus, 1000);
});
</script>
@endsection