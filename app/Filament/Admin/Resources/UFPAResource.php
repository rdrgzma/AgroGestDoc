<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UFPAResource\Pages;
use App\Filament\Resources\UfpaResource\RelationManagers\ProducoesRelationManager;
use App\Models\Ufpa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class UfpaResource extends Resource
{
    protected static ?string $model = Ufpa::class;
    protected static ?string $navigationLabel = 'UFPA';

    protected static ?string $navigationIcon = 'heroicon-o-home';
   //protected static ?string $navigationGroup = 'Cadastros';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }


    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('person_id')
                ->relationship('pessoa', 'nome')
                ->required(),
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

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('pessoa.nome')->label('Pessoa'),
            Tables\Columns\TextColumn::make('nome_propriedade'),
            Tables\Columns\TextColumn::make('tipo_posse'),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            ProducoesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUfpas::route('/'),
            'create' => Pages\CreateUfpa::route('/create'),
            'edit' => Pages\EditUfpa::route('/{record}/edit'),
        ];
    }

    protected static function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();
        return $data;
    }

    protected static function mutateFormDataBeforeSave(array $data): array
    {
        $data['created_by'] = Auth::id();
        return $data;
    }
}
