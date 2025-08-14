<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PersonResource\Pages;
use App\Filament\Admin\Resources\PersonResource\RelationManagers\UfpasRelationManager;
use App\Models\Person;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PersonResource extends Resource
{
    protected static ?string $model = Person::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
  // protected static ?string $navigationGroup = 'Cadastros';
    protected static ?string $navigationLabel = 'Pessoas';
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nome')->required(),
            Forms\Components\TextInput::make('cpf')->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('rg')->nullable(),
            Forms\Components\TextInput::make('endereco')->nullable(),
            Forms\Components\TextInput::make('municipio')->nullable(),
            Forms\Components\TextInput::make('uf')->nullable(),
            Forms\Components\TextInput::make('email')->nullable()->email(),
            Forms\Components\TextInput::make('telefone')->nullable(),
            Forms\Components\Select::make('estado_civil')
                ->options([
                    'solteiro' => 'Solteiro',
                    'casado' => 'Casado',
                    'divorciado' => 'Divorciado',
                    'viuvo' => 'Viúvo',
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('nome')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('cpf'),
            Tables\Columns\TextColumn::make('municipio'),
            Tables\Columns\TextColumn::make('uf'),
            Tables\Columns\TextColumn::make('estado_civil'),
            Tables\Columns\TextColumn::make('creator.name')->label('Criado por'),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            UfpasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeople::route('/'),
            'create' => Pages\CreatePerson::route('/create'),
            'edit' => Pages\EditPerson::route('/{record}/edit'),
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
