<?php

namespace App\Filament\Admin\Resources\PersonResource\RelationManagers;

use App\Models\Ufpa;
use App\Services\DocumentGeneratorServiceAi;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class UfpasRelationManager extends RelationManager
{
    protected static string $relationship = 'ufpas';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nome_propriedade')->required(),
            Forms\Components\TextInput::make('area_total')->numeric(),
            Forms\Components\TextInput::make('localizacao'),
            Forms\Components\TextInput::make('matricula'),
            Forms\Components\TextInput::make('nirf'),
            Forms\Components\TextInput::make('ccir'),
            Forms\Components\TextInput::make('car'),
            Forms\Components\Select::make('tipo_posse')->options([
                'própria' => 'Própria',
                'arrendada' => 'Arrendada',
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('nome_propriedade'),
            Tables\Columns\TextColumn::make('area_total'),
            Tables\Columns\TextColumn::make('tipo_posse'),
            Tables\Columns\TextColumn::make('created_at')->date(),
        ])
            ->actions([
                Action::make('gerar_docx')
                    ->label('Gerar DOCX')
                    ->icon('heroicon-o-document-text')
                    ->action(function ($record) {
                        $filePath = DocumentGeneratorServiceAi::generateDocx(
                            storage_path('app/templates/contrato.docx'),
                            [
                                'nome' => $record->pessoa->nome,
                                'cpf'  => $record->pessoa->cpf,
                                'propriedade' => $record->nome_propriedade,
                            ],
                            'contrato_' . $record->id
                        );

                        return Response::download($filePath);
                    }),
                Action::make('gerar_pdf')
                    ->label('Gerar PDF')
                    ->icon('heroicon-o-document')
                    ->action(function ($record) {
                        $docxPath = DocumentGeneratorServiceAi::generateDocx(
                            storage_path('app/templates/contrato.docx'),
                            [
                                'nome' => $record->pessoa->nome,
                                'cpf'  => $record->pessoa->cpf,
                                'propriedade' => $record->nome_propriedade,
                            ],
                            'contrato_' . $record->id
                        );

                        $pdfPath = DocumentGeneratorServiceAi::generatePdfFromDocx($docxPath, 'contrato_' . $record->id);
                        return Response::download($pdfPath);
                    }),
            ]);

    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['created_by'] = Auth::id();
        return $data;
    }
}

