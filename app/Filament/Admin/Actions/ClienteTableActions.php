<?php

namespace App\Filament\Admin\Actions;

use App\Models\Cliente;
use App\Services\DocumentGeneratorServiceAi as DocumentGeneratorService;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;
use Filament\Notifications\Notification;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions;

class ClienteTableActions
{
    public static function getDocumentActions(): array
    {
        return [
            ActionGroup::make([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
                self::generateSingleDocumentAction(),
                self::generateAllDocumentsAction(),
                self::generateCustomDocumentsAction(),
            ])
                ->label('Documentos')
                ->icon('heroicon-m-document-text')
                ->size('sm')
                ->color('success')
                ->button()
        ];
    }

    protected static function generateSingleDocumentAction(): Action
    {
        return Action::make('generateSingle')
            ->label('Gerar Documento')
            ->icon('heroicon-o-document-plus')
            ->color('info')
            ->form([
                Select::make('document_type')
                    ->label('Tipo de Documento')
                    ->options(function (Cliente $record) {
                        return app(DocumentGeneratorService::class)->getAvailableDocuments($record);
                    })
                    ->required()
                    ->native(false)
                    ->searchable(),
            ])
            ->modalWidth(MaxWidth::Medium)
            ->modalHeading('Gerar Documento Individual')
            ->modalDescription('Selecione o tipo de documento que deseja gerar para este cliente.')
            ->action(function (array $data, Cliente $record): void {
                try {
                    $documentService = app(DocumentGeneratorService::class);
                    $filePath = $documentService->generateDocument($record, $data['document_type']);

                    // Redirecionar para download
                    redirect()->to(route('download.document', ['file' => basename($filePath)]));

                    Notification::make()
                        ->title('Documento gerado com sucesso!')
                        ->body("Documento gerado para {$record->nome_completo}")
                        ->success()
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Erro ao gerar documento')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }

    protected static function generateAllDocumentsAction(): Action
    {
        return Action::make('generateAll')
            ->label('Gerar Todos')
            ->icon('heroicon-o-document-duplicate')
            ->color('success')
            ->requiresConfirmation()
            ->modalHeading('Gerar Todos os Documentos')
            ->modalDescription(function (Cliente $record): string {
                $documentService = app(DocumentGeneratorService::class);
                $availableDocuments = $documentService->getAvailableDocuments($record);
                $count = count($availableDocuments);

                return "Deseja gerar todos os {$count} documentos disponíveis para {$record->nome_completo}?";
            })
            ->modalSubmitActionLabel('Gerar Todos')
            ->action(function (Cliente $record): void {
                try {
                    $documentService = app(DocumentGeneratorService::class);
                    $zipPath = $documentService->generateAllDocuments($record);

                    // Redirecionar para download do ZIP
                    redirect()->to(route('download.document', ['file' => basename($zipPath)]));

                    Notification::make()
                        ->title('Documentos gerados com sucesso!')
                        ->body("Todos os documentos foram gerados para {$record->nome_completo}")
                        ->success()
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Erro ao gerar documentos')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }

    protected static function generateCustomDocumentsAction(): Action
    {
        return Action::make('generateCustom')
            ->label('Gerar Selecionados')
            ->icon('heroicon-o-document-check')
            ->color('warning')
            ->form([
                CheckboxList::make('selected_documents')
                    ->label('Documentos Disponíveis')
                    ->options(function (Cliente $record) {
                        return app(DocumentGeneratorService::class)->getAvailableDocuments($record);
                    })
                    ->required()
                    ->columns(2)
                    ->descriptions([
                        'caf_declaracao' => 'Declaração de escolha da empresa',
                        'caf_formulario_casal' => 'Formulário CAF para casal',
                        'caf_formulario_solteiro' => 'Formulário CAF para solteiro',
                        'declaracao_incra_casal' => 'Declaração INCRA para casal',
                        'declaracao_incra_solteiro' => 'Declaração INCRA para solteiro',
                        'autodeclaracao_segurado' => 'Autodeclaração do Segurado Especial',
                        'contrato_prestacao' => 'Contrato de prestação de serviços',
                        'declaracao_posse_renda' => 'Declaração de posse e renda',
                        'ficha_proposta_titular' => 'Ficha proposta do titular',
                        'ficha_proposta_conjuge' => 'Ficha proposta do cônjuge',
                        'termo_representacao' => 'Termo de representação',
                    ]),
            ])
            ->modalWidth(MaxWidth::Large)
            ->modalHeading('Gerar Documentos Selecionados')
            ->modalDescription('Selecione os documentos que deseja gerar para este cliente.')
            ->action(function (array $data, Cliente $record): void {
                try {
                    $documentService = app(DocumentGeneratorService::class);
                    $generatedFiles = [];

                    foreach ($data['selected_documents'] as $documentType) {
                        $filePath = $documentService->generateDocument($record, $documentType);
                        $generatedFiles[] = $filePath;
                    }

                    if (count($generatedFiles) > 1) {
                        // Criar ZIP se múltiplos documentos
                        $zipPath = $documentService->createZipFile($record, $generatedFiles);
                        redirect()->to(route('download.document', ['file' => basename($zipPath)]));
                    } elseif (count($generatedFiles) === 1) {
                        // Download direto se apenas um documento
                        redirect()->to(route('download.document', ['file' => basename($generatedFiles[0])]));
                    }

                    Notification::make()
                        ->title('Documentos gerados com sucesso!')
                        ->body(count($generatedFiles) . ' documento(s) gerado(s) para ' . $record->nome_completo)
                        ->success()
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Erro ao gerar documentos')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }

    public static function getBulkActions(): array
    {
        return [
            Actions\DeleteBulkAction::make(),
            \Filament\Tables\Actions\BulkAction::make('generateAllForSelected')
                ->label('Gerar Todos os Documentos')
                ->icon('heroicon-o-document-duplicate')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Gerar Documentos em Lote')
                ->modalDescription(function (\Illuminate\Database\Eloquent\Collection $records): string {
                    $count = $records->count();
                    return "Deseja gerar todos os documentos para os {$count} clientes selecionados? Esta operação pode demorar alguns minutos.";
                })
                ->modalSubmitActionLabel('Gerar Todos')
                ->action(function (\Illuminate\Database\Eloquent\Collection $records): void {
                    $documentService = app(DocumentGeneratorService::class);
                    $successCount = 0;
                    $errors = [];

                    foreach ($records as $client) {
                        try {
                            $documentService->generateAllDocuments($client);
                            $successCount++;
                        } catch (\Exception $e) {
                            $errors[] = "Erro para {$client->nome_completo}: " . $e->getMessage();
                        }
                    }

                    if ($successCount > 0) {
                        Notification::make()
                            ->title('Documentos gerados!')
                            ->body("Documentos gerados para {$successCount} cliente(s)")
                            ->success()
                            ->send();
                    }

                    if (!empty($errors)) {
                        Notification::make()
                            ->title('Alguns erros ocorreram')
                            ->body(implode('; ', $errors))
                            ->warning()
                            ->send();
                    }
                }),

            \Filament\Tables\Actions\BulkAction::make('generateCustomForSelected')
                ->label('Gerar Documentos Selecionados')
                ->icon('heroicon-o-document-check')
                ->color('warning')
                ->form([
                    CheckboxList::make('document_types')
                        ->label('Tipos de Documento')
                        ->options([
                            'caf_declaracao' => 'CAF - Declaração',
                            'autodeclaracao_segurado' => 'Autodeclaração do Segurado',
                            'contrato_prestacao' => 'Contrato de Prestação',
                            'termo_representacao' => 'Termo de Representação',
                        ])
                        ->required()
                        ->descriptions([
                            'caf_declaracao' => 'Disponível para todos os clientes',
                            'autodeclaracao_segurado' => 'Disponível para todos os clientes',
                            'contrato_prestacao' => 'Disponível para todos os clientes',
                            'termo_representacao' => 'Disponível para todos os clientes',
                        ]),
                ])
                ->modalWidth(MaxWidth::Large)
                ->modalHeading('Gerar Documentos para Múltiplos Clientes')
                ->action(function (array $data, \Illuminate\Database\Eloquent\Collection $records): void {
                    $documentService = app(DocumentGeneratorService::class);
                    $successCount = 0;

                    foreach ($records as $client) {
                        foreach ($data['document_types'] as $documentType) {
                            try {
                                $availableDocuments = $documentService->getAvailableDocuments($client);
                                if (array_key_exists($documentType, $availableDocuments)) {
                                    $documentService->generateDocument($client, $documentType);
                                }
                                $successCount++;
                            } catch (\Exception $e) {
                                // Log error but continue
                                \Log::error("Erro ao gerar documento {$documentType} para cliente {$client->id}: " . $e->getMessage());
                            }
                        }
                    }

                    Notification::make()
                        ->title('Documentos gerados!')
                        ->body("{$successCount} documento(s) gerado(s) com sucesso")
                        ->success()
                        ->send();
                }),
        ];
    }
}
