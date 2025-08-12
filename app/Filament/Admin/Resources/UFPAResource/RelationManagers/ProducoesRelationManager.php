<?php

namespace App\Filament\Resources\UfpaResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;

use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ProducoesRelationManager extends RelationManager
{
    protected static string $relationship = 'producoes';

    public function form(Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('produto')->required(),
            Forms\Components\TextInput::make('quantidade')->numeric(),
            Forms\Components\TextInput::make('unidade'),
            Forms\Components\TextInput::make('ano')->numeric()->minValue(1900)->maxValue(now()->year),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('produto'),
            Tables\Columns\TextColumn::make('quantidade'),
            Tables\Columns\TextColumn::make('unidade'),
            Tables\Columns\TextColumn::make('ano'),
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

