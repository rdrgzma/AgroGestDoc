<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ContaResource\Pages;
use App\Filament\Admin\Resources\ContaResource\RelationManagers;
use App\Models\Conta;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContaResource extends Resource
{
    protected static ?string $model = Conta::class;

    protected static ?string $navigationGroup = 'Financeiro';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('cliente_id')
                    ->label('Cliente')
                    ->searchable()
                    ->relationship('cliente', 'nome_completo'),
                Forms\Components\Select::make('tipo')
                    ->label('Tipo')
                    ->options(['pagar', 'receber'])
                    ->default('pagar')
                    ->required(),
                Forms\Components\TextInput::make('descricao')
                   ,
                Forms\Components\TextInput::make('valor')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('vencimento')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options(['pendente', 'pago', 'atrasado'])
                    ->required(),
                Forms\Components\Select::make('forma_pagamento')
                    ->label('Forma de Pagamento')
                    ->options(['PIX','Crédito','Débito','Boleto Bancário','Outros']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cliente.nome_completo')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('descricao')
                    ->searchable(),
                Tables\Columns\TextColumn::make('valor')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vencimento')
                    ->date()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('forma_pagamento')
                    ->label('Forma de Pagamento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_by')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_by')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContas::route('/'),
            'create' => Pages\CreateConta::route('/create'),
            'edit' => Pages\EditConta::route('/{record}/edit'),
        ];
    }
}
