<?php

namespace App\Filament\Admin\Pages;

use App\Models\Cliente;
use App\Services\DocumentGeneratorServiceAi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;

class DocumentGeneratePage extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Gerador de Documentos';
    protected static string $view = 'filament.admin.pages.document-generate-page';
    protected static ?string $title = 'Gerador de Documentos';
    protected static ?string $navigationGroup = 'Documentos';
    protected static ?int $navigationSort = 1;

    public ?array $data = [];
    public ?int $selectedClientId = null;
    public array $selectedDocuments = [];

    protected DocumentGeneratorServiceAi $documentService;

    public function boot(DocumentGeneratorServiceAi $documentService): void
    {
        $this->documentService = $documentService;
    }

    public function mount(): void
    {
        $documentService = new DocumentGeneratorServiceAi();
        $this->documentService = $documentService;
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Seleção de Cliente')
                    ->schema([
                        Forms\Components\Select::make('selectedClientId')
                            ->label('Cliente')
                            ->placeholder('Selecione um cliente')
                            //->options(Cliente::all()->pluck('nome_completo', 'id'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn () => $this->updateAvailableDocuments()),

                        Forms\Components\CheckboxList::make('selectedDocuments')
                            ->label('Documentos Disponíveis')
                            ->options($this->getAvailableDocuments())
                            ->visible(fn () => !empty($this->selectedClientId))
                            ->columns(2)
                            ->descriptions($this->getDocumentDescriptions()),
                    ])
                    ->columns(1),
            ])
            ->statePath('data');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Cliente::query())
            ->columns([
                Tables\Columns\TextColumn::make('nome_completo')
                    ->label('Nome Completo')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('cpf')
                    ->label('CPF')
                    ->formatStateUsing(fn (string $state): string =>
                    preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $state)
                    ),

                Tables\Columns\TextColumn::make('estado_civil')
                    ->label('Estado Civil')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'solteiro' => 'gray',
                        'casado', 'união estável' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('municipio')
                    ->label('Município')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data de Cadastro')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado_civil')
                    ->label('Estado Civil')
                    ->options([
                        'solteiro' => 'Solteiro',
                        'casado' => 'Casado',
                        'união estável' => 'União Estável',
                        'divorciado' => 'Divorciado',
                        'viúvo' => 'Viúvo',
                    ]),

                Tables\Filters\SelectFilter::make('municipio')
                    ->label('Município')
                    ->options(fn (): array => Cliente::distinct()->pluck('municipio', 'municipio')->toArray()),
            ])
            ->actions([
                Tables\Actions\Action::make('generateSingle')
                    ->label('Gerar Documento')
                    ->icon('heroicon-o-document-plus')
                    ->color('info')
                    ->form([
                        Forms\Components\Select::make('document_type')
                            ->label('Tipo de Documento')
                            ->options(function (Cliente $record) {
                                return $this->documentService->getAvailableDocuments($record);
                            })
                            ->required(),
                    ])
                    ->action(function (array $data, Cliente $record): void {
                        $this->generateSingleDocument($record, $data['document_type']);
                    }),

                Tables\Actions\Action::make('generateAll')
                    ->label('Gerar Todos')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Gerar Todos os Documentos')
                    ->modalDescription('Deseja gerar todos os documentos disponíveis para este cliente?')
                    ->action(fn (Cliente $record) => $this->generateAllDocuments($record)),

                Tables\Actions\Action::make('viewClient')
                    ->label('Ver Detalhes')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->modalContent(fn (Cliente $record): View => view(
                        'filament.modals.client-details',
                        ['client' => $record]
                    ))
                    ->modalWidth(MaxWidth::Large),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('generateAllForSelected')
                    ->label('Gerar Todos os Documentos')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Gerar Documentos em Lote')
                    ->modalDescription('Deseja gerar todos os documentos para os clientes selecionados?')
                    ->action(function (\Illuminate\Database\Eloquent\Collection $records): void {
                        foreach ($records as $client) {
                            $this->generateAllDocuments($client);
                        }
                    }),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateSelected')
                ->label('Gerar Documentos Selecionados')
                ->icon('heroicon-o-document-plus')
                ->color('success')
                ->visible(fn () => !empty($this->selectedClientId) && !empty($this->selectedDocuments))
                ->action('generateSelectedDocuments'),

            Action::make('generateAllForClient')
                ->label('Gerar Todos os Documentos')
                ->icon('heroicon-o-document-duplicate')
                ->color('info')
                ->visible(fn () => !empty($this->selectedClientId))
                ->requiresConfirmation()
                ->action('generateAllForSelectedClient'),

            Action::make('downloadTemplates')
                ->label('Baixar Templates')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->url(route('download.templates')),
        ];
    }

    public function generateSelectedDocuments(): void
    {
        if (empty($this->selectedClientId) || empty($this->selectedDocuments)) {
            Notification::make()
                ->title('Erro')
                ->body('Selecione um cliente e pelo menos um documento.')
                ->danger()
                ->send();
            return;
        }

        $client = Cliente::find($this->selectedClientId);
        $generatedFiles = [];

        foreach ($this->selectedDocuments as $documentType) {
            try {
                $filePath = $this->documentService->generateDocument($client, $documentType);
                $generatedFiles[] = $filePath;
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Erro ao gerar documento')
                    ->body("Erro ao gerar {$documentType}: " . $e->getMessage())
                    ->danger()
                    ->send();
            }
        }

        if (count($generatedFiles) > 1) {
            // Criar ZIP se múltiplos documentos
            $zipPath = $this->documentService->createZipFile($client, $generatedFiles);
            $this->downloadFile($zipPath);
        } elseif (count($generatedFiles) === 1) {
            // Download direto se apenas um documento
            $this->downloadFile($generatedFiles[0]);
        }

        Notification::make()
            ->title('Documentos gerados com sucesso!')
            ->body(count($generatedFiles) . ' documento(s) gerado(s) para ' . $client->nome_completo)
            ->success()
            ->send();
    }

    public function generateAllForSelectedClient(): void
    {
        if (empty($this->selectedClientId)) {
            Notification::make()
                ->title('Erro')
                ->body('Selecione um cliente.')
                ->danger()
                ->send();
            return;
        }

        $client = Cliente::find($this->selectedClientId);
        $this->generateAllDocuments($client);
    }

    protected function generateSingleDocument(Cliente $client, string $documentType): void
    {
        try {
            $filePath = $this->documentService->generateDocument($client, $documentType);
            $this->downloadFile($filePath);

            Notification::make()
                ->title('Documento gerado com sucesso!')
                ->body("Documento gerado para {$client->nome_completo}")
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Erro ao gerar documento')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function generateAllDocuments(Cliente $client): void
    {
        try {
            $zipPath = $this->documentService->generateAllDocuments($client);
            $this->downloadFile($zipPath);

            Notification::make()
                ->title('Documentos gerados com sucesso!')
                ->body("Todos os documentos foram gerados para {$client->nome_completo}")
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Erro ao gerar documentos')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function downloadFile(string $filePath): void
    {
        if (file_exists($filePath)) {
            $this->redirect(route('download.document', ['file' => basename($filePath)]));
        }
    }

    protected function updateAvailableDocuments(): void
    {
        $this->selectedDocuments = [];
    }

    protected function getAvailableDocuments(): array
    {
        if (empty($this->selectedClientId)) {
            return [];
        }

        $client = Cliente::find($this->selectedClientId);
        return $client ? $this->documentService->getAvailableDocuments($client) : [];
    }

    protected function getDocumentDescriptions(): array
    {
        return [
            'caf_declaracao' => 'Declaração de escolha da empresa para prestação de assistência técnica',
            'caf_formulario_casal' => 'Formulário CAF para casal',
            'caf_formulario_solteiro' => 'Formulário CAF para pessoa solteira',
            'declaracao_incra_casal' => 'Declaração INCRA para casal',
            'declaracao_incra_solteiro' => 'Declaração INCRA para pessoa solteira',
            'autodeclaracao_segurado' => 'Autodeclaração do Segurado Especial Rural',
            'contrato_prestacao' => 'Contrato de prestação de serviços',
            'declaracao_posse_renda' => 'Declaração de posse e renda',
            'ficha_proposta_titular' => 'Ficha proposta do titular',
            'ficha_proposta_conjuge' => 'Ficha proposta do cônjuge',
            'termo_representacao' => 'Termo de representação e autorização',
        ];
    }
}
