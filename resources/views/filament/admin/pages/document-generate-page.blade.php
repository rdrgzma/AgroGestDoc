<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Formulário de Seleção --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                Geração Individual de Documentos
            </h3>

            {{ $this->form }}

            @if(!empty($this->selectedClientId))
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @php
                            $client = \App\Models\Cliente::find($this->selectedClientId);
                        @endphp

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900">Cliente Selecionado</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ $client->nome_completo ?? '' }}</p>
                            <p class="text-sm text-gray-600">{{ $client->estado_civil ?? '' }}</p>
                        </div>

                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-medium text-blue-900">Documentos Disponíveis</h4>
                            <p class="text-sm text-blue-600 mt-1">
                                {{ count($this->getAvailableDocuments()) }} documento(s)
                            </p>
                        </div>

                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-medium text-green-900">Documentos Selecionados</h4>
                            <p class="text-sm text-green-600 mt-1">
                                {{ count($this->selectedDocuments) }} documento(s)
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Estatísticas --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                            <x-heroicon-o-users class="w-5 h-5 text-blue-600" />
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Total de Clientes</h3>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ \App\Models\Cliente::count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                            <x-heroicon-o-heart class="w-5 h-5 text-green-600" />
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Casados</h3>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ \App\Models\Cliente::whereIn('estado_civil', ['casado', 'união estável'])->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                            <x-heroicon-o-user class="w-5 h-5 text-yellow-600" />
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Solteiros</h3>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ \App\Models\Cliente::where('estado_civil', 'solteiro')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                            <x-heroicon-o-document-text class="w-5 h-5 text-purple-600" />
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Tipos de Documento</h3>
                        <p class="text-2xl font-semibold text-gray-900">11</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabela de Clientes --}}
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    Lista de Clientes
                </h3>
                <p class="text-sm text-gray-600 mt-1">
                    Selecione um cliente para gerar documentos individuais ou use as ações em lote
                </p>
            </div>

            {{ $this->table }}
        </div>

        {{-- Instruções --}}
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <x-heroicon-o-information-circle class="h-5 w-5 text-blue-400" />
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                        Como usar o gerador de documentos
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Para gerar documentos individuais, selecione um cliente no formulário acima</li>
                            <li>Para gerar um documento específico, use o botão "Gerar Documento" na tabela</li>
                            <li>Para gerar todos os documentos de um cliente, use o botão "Gerar Todos"</li>
                            <li>Use as ações em lote para processar múltiplos clientes simultaneamente</li>
                            <li>Os documentos são gerados baseados no estado civil e situação de renda do cliente</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
