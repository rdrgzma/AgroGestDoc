<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DocumentoGeradoResource\Pages;
use App\Filament\Admin\Resources\DocumentoGeradoResource\RelationManagers;
use App\Models\DocumentoGerado;
use Filament\Forms;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentoGeradoResource extends Resource
{
    protected static ?string $model = DocumentoGerado::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Documentos Gerados';
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    BelongsToSelect::make('person_id')
                        ->label('Pessoa')
                        ->relationship('person', 'nome')
                        ->searchable()
                        ->required(),

                    TextInput::make('tipo')->label('Tipo (ex: contrato, declaracao)')->required(),
                    Select::make('formato')->label('Formato')->options([
                        'pdf' => 'PDF',
                        'docx' => 'DOCX',
                    ])->required(),

                    FileUpload::make('arquivo')
                        ->label('Arquivo')
                        ->directory('documentos')
                        ->preserveFilenames()
                        ->required(),

                    TextInput::make('created_by')->hidden(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListDocumentoGerados::route('/'),
            'create' => Pages\CreateDocumentoGerado::route('/create'),
            'edit' => Pages\EditDocumentoGerado::route('/{record}/edit'),
        ];
    }
}
