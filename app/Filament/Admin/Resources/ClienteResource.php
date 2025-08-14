<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Actions\ClienteTableActions;
use App\Filament\Admin\Resources\ClienteResource\Pages;
use App\Models\Cliente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Clientes';
    protected static ?string $modelLabel = 'Cliente';
    protected static ?string $pluralModelLabel = 'Clientes';
    protected static ?string $navigationGroup = 'Cadastros';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Dados do Cliente')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Dados Pessoais')
                            ->schema([
                                Forms\Components\Section::make('Informações Básicas')
                                    ->schema([
                                        Forms\Components\TextInput::make('nome_completo')
                                            ->label('Nome Completo')
                                            ->required()
                                            ->maxLength(255),

                                        Forms\Components\TextInput::make('cpf')
                                            ->label('CPF')
                                            ->mask('999.999.999-99')
                                            ->required()
                                            ->unique(ignoreRecord: true),

                                        Forms\Components\TextInput::make('rg')
                                            ->label('RG')
                                            ->maxLength(20),

                                        Forms\Components\TextInput::make('orgao_expedidor')
                                            ->label('Órgão Expedidor')
                                            ->maxLength(10),

                                        Forms\Components\DatePicker::make('data_nascimento')
                                            ->label('Data de Nascimento')
                                            ->native(false),

                                        Forms\Components\TextInput::make('local_nascimento')
                                            ->label('Local de Nascimento'),

                                        Forms\Components\Select::make('estado_civil')
                                            ->label('Estado Civil')
                                            ->options(Cliente::getEstadosCivis())
                                            ->required()
                                            ->native(false)
                                            ->live(),

                                        Forms\Components\Select::make('sexo')
                                            ->label('Sexo')
                                            ->options([
                                                'M' => 'Masculino',
                                                'F' => 'Feminino',
                                            ])
                                            ->required()
                                            ->native(false),

                                        Forms\Components\TextInput::make('nome_mae')
                                            ->label('Nome da Mãe'),

                                        Forms\Components\TextInput::make('apelido')
                                            ->label('Apelido')
                                            ->maxLength(50),

                                        Forms\Components\Select::make('cor_raca')
                                            ->label('Cor/Raça')
                                            ->options(Cliente::getCoresRaca())
                                            ->native(false),

                                        Forms\Components\TextInput::make('escolaridade')
                                            ->label('Escolaridade'),
                                    ])
                                    ->columns(3),

                                Forms\Components\Section::make('Contato')
                                    ->schema([
                                        Forms\Components\TextInput::make('telefone')
                                            ->label('Telefone')
                                            ->mask('(99) 99999-9999')
                                            ->maxLength(15),

                                        Forms\Components\TextInput::make('email')
                                            ->label('E-mail')
                                            ->email(),
                                    ])
                                    ->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Dados do Cônjuge')
                            ->schema([
                                Forms\Components\Section::make('Informações do Cônjuge')
                                    ->schema([
                                        Forms\Components\TextInput::make('nome_conjuge')
                                            ->label('Nome Completo do Cônjuge'),

                                        Forms\Components\TextInput::make('cpf_conjuge')
                                            ->label('CPF do Cônjuge')
                                            ->mask('999.999.999-99'),

                                        Forms\Components\TextInput::make('rg_conjuge')
                                            ->label('RG do Cônjuge')
                                            ->maxLength(20),

                                        Forms\Components\DatePicker::make('data_nascimento_conjuge')
                                            ->label('Data de Nascimento do Cônjuge')
                                            ->native(false),

                                        Forms\Components\TextInput::make('local_nascimento_conjuge')
                                            ->label('Local de Nascimento do Cônjuge'),

                                        Forms\Components\TextInput::make('apelido_conjuge')
                                            ->label('Apelido do Cônjuge')
                                            ->maxLength(50),

                                        Forms\Components\Select::make('cor_raca_conjuge')
                                            ->label('Cor/Raça do Cônjuge')
                                            ->options(Cliente::getCoresRaca())
                                            ->native(false),

                                        Forms\Components\TextInput::make('escolaridade_conjuge')
                                            ->label('Escolaridade do Cônjuge'),
                                    ])
                                    ->columns(2)
                                    ->visible(fn (Forms\Get $get): bool =>
                                    in_array($get('estado_civil'), ['casado', 'união estável'])
                                    ),
                            ]),

                        Forms\Components\Tabs\Tab::make('Endereço')
                            ->schema([
                                Forms\Components\Section::make('Endereço Residencial')
                                    ->schema([
                                        Forms\Components\TextInput::make('endereco')
                                            ->label('Logradouro')
                                            ->required(),

                                        Forms\Components\TextInput::make('numero')
                                            ->label('Número')
                                            ->maxLength(10),

                                        Forms\Components\TextInput::make('complemento')
                                            ->label('Complemento'),

                                        Forms\Components\TextInput::make('bairro')
                                            ->label('Bairro'),

                                        Forms\Components\TextInput::make('municipio')
                                            ->label('Município')
                                            ->required(),

                                        Forms\Components\TextInput::make('uf')
                                            ->label('UF')
                                            ->maxLength(2)
                                            ->required(),

                                        Forms\Components\TextInput::make('cep')
                                            ->label('CEP')
                                            ->mask('99999-999'),
                                    ])
                                    ->columns(3),

                                Forms\Components\Toggle::make('endereco_correspondencia_diferente')
                                    ->label('Endereço para correspondência é diferente?')
                                    ->live(),

                                Forms\Components\Section::make('Endereço para Correspondência')
                                    ->schema([
                                        Forms\Components\TextInput::make('endereco_correspondencia')
                                            ->label('Logradouro'),

                                        Forms\Components\TextInput::make('numero_correspondencia')
                                            ->label('Número')
                                            ->maxLength(10),

                                        Forms\Components\TextInput::make('complemento_correspondencia')
                                            ->label('Complemento'),

                                        Forms\Components\TextInput::make('bairro_correspondencia')
                                            ->label('Bairro'),

                                        Forms\Components\TextInput::make('municipio_correspondencia')
                                            ->label('Município'),

                                        Forms\Components\TextInput::make('uf_correspondencia')
                                            ->label('UF')
                                            ->maxLength(2),

                                        Forms\Components\TextInput::make('cep_correspondencia')
                                            ->label('CEP')
                                            ->mask('99999-999'),
                                    ])
                                    ->columns(3)
                                    ->visible(fn (Forms\Get $get): bool =>
                                    $get('endereco_correspondencia_diferente')
                                    ),
                            ]),

                        Forms\Components\Tabs\Tab::make('Propriedade Rural')
                            ->schema([
                                Forms\Components\Section::make('Informações do Projeto/Assentamento')
                                    ->schema([
                                        Forms\Components\TextInput::make('projeto_assentamento')
                                            ->label('Projeto de Assentamento'),

                                        Forms\Components\TextInput::make('codigo_sipra')
                                            ->label('Código SIPRA')
                                            ->maxLength(20),

                                        Forms\Components\Toggle::make('beneficiario_credito_fundiario')
                                            ->label('Beneficiário do Crédito Fundiário'),

                                        Forms\Components\Toggle::make('gestor_ufpa')
                                            ->label('Gestor da UFPA'),

                                        Forms\Components\Toggle::make('mao_obra_ufpa')
                                            ->label('Mão de Obra na UFPA'),

                                        Forms\Components\Toggle::make('assentado_reforma_agraria')
                                            ->label('Assentado da Reforma Agrária'),

                                        Forms\Components\Toggle::make('area_indigena_quilombola')
                                            ->label('Área Indígena/Quilombola'),

                                        Forms\Components\Toggle::make('pescador_artesanal')
                                            ->label('Pescador Artesanal'),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make('Área de Produção')
                                    ->schema([
                                        Forms\Components\Select::make('tipo_area')
                                            ->label('Tipo de Área')
                                            ->options(Cliente::getTiposArea())
                                            ->native(false),

                                        Forms\Components\TextInput::make('medida_area')
                                            ->label('Medida')
                                            ->default('ha'),

                                        Forms\Components\TextInput::make('area_total')
                                            ->label('Área Total')
                                            ->numeric()
                                            ->step(0.01),

                                        Forms\Components\TextInput::make('area_explorada')
                                            ->label('Área Explorada')
                                            ->numeric()
                                            ->step(0.01),

                                        Forms\Components\TextInput::make('coordenada_latitude')
                                            ->label('Latitude')
                                            ->maxLength(20),

                                        Forms\Components\TextInput::make('coordenada_longitude')
                                            ->label('Longitude')
                                            ->maxLength(20),
                                    ])
                                    ->columns(3),
                            ]),

                        Forms\Components\Tabs\Tab::make('Atividade Rural')
                            ->schema([
                                Forms\Components\Section::make('Informações de Atividade')
                                    ->schema([
                                        Forms\Components\TextInput::make('atividade_principal')
                                            ->label('Atividade Principal')
                                            ->default('Pesca artesanal'),

                                        Forms\Components\Textarea::make('outras_atividades')
                                            ->label('Outras Atividades')
                                            ->rows(3),

                                        Forms\Components\DatePicker::make('periodo_atividade_inicio')
                                            ->label('Início da Atividade')
                                            ->native(false),

                                        Forms\Components\DatePicker::make('periodo_atividade_fim')
                                            ->label('Fim da Atividade')
                                            ->native(false),

                                        Forms\Components\Select::make('condicao_imovel')
                                            ->label('Condição em Relação ao Imóvel')
                                            ->options(Cliente::getCondicoesImovel())
                                            ->native(false),

                                        Forms\Components\Select::make('situacao_atividade')
                                            ->label('Situação da Atividade')
                                            ->options([
                                                'Individualmente' => 'Individualmente',
                                                'Regime de economia familiar' => 'Regime de economia familiar',
                                            ])
                                            ->native(false),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make('Informações de Renda')
                                    ->schema([
                                        Forms\Components\TextInput::make('renda_bruta_estabelecimento')
                                            ->label('Renda Bruta do Estabelecimento (R$)')
                                            ->numeric()
                                            ->step(0.01)
                                            ->prefix('R$'),

                                        Forms\Components\TextInput::make('renda_fora_estabelecimento')
                                            ->label('Renda Fora do Estabelecimento (R$)')
                                            ->numeric()
                                            ->step(0.01)
                                            ->prefix('R$'),

                                        Forms\Components\TextInput::make('beneficios_sociais')
                                            ->label('Benefícios Sociais (R$)')
                                            ->numeric()
                                            ->step(0.01)
                                            ->prefix('R$'),
                                    ])
                                    ->columns(3),

                                Forms\Components\Section::make('Mão de Obra')
                                    ->schema([
                                        Forms\Components\TextInput::make('pessoas_familia_residentes')
                                            ->label('Pessoas da Família Residentes')
                                            ->numeric()
                                            ->minValue(0),

                                        Forms\Components\TextInput::make('pessoas_familia_trabalham')
                                            ->label('Pessoas da Família que Trabalham')
                                            ->numeric()
                                            ->minValue(0),

                                        Forms\Components\TextInput::make('empregados_permanentes')
                                            ->label('Empregados Permanentes')
                                            ->numeric()
                                            ->minValue(0),
                                    ])
                                    ->columns(3),
                            ]),

                        Forms\Components\Tabs\Tab::make('Informações Bancárias')
                            ->schema([
                                Forms\Components\Section::make('Dados Bancários')
                                    ->schema([
                                        Forms\Components\TextInput::make('banco_agencia')
                                            ->label('Banco/Agência')
                                            ->maxLength(50),

                                        Forms\Components\TextInput::make('conta_corrente')
                                            ->label('Conta Corrente')
                                            ->maxLength(20),

                                        Forms\Components\Toggle::make('pessoa_exposta_politicamente')
                                            ->label('Pessoa Exposta Politicamente (PEP)'),
                                    ])
                                    ->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Observações')
                            ->schema([
                                Forms\Components\Section::make('Informações Adicionais')
                                    ->schema([
                                        Forms\Components\Textarea::make('observacoes')
                                            ->label('Observações')
                                            ->rows(4),

                                        Forms\Components\Toggle::make('ativo')
                                            ->label('Cliente Ativo')
                                            ->default(true),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome_completo')
                    ->label('Nome Completo')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('cpf')
                    ->label('CPF')
                    ->formatStateUsing(fn (string $state): string =>
                    preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $state)
                    )
                    ->searchable(),

                Tables\Columns\TextColumn::make('estado_civil')
                    ->label('Estado Civil')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'solteiro' => 'gray',
                        'casado', 'união estável' => 'success',
                        'divorciado' => 'warning',
                        'viúvo' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('municipio')
                    ->label('Município')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('telefone')
                    ->label('Telefone')
                    ->formatStateUsing(function (?string $state): string {
                        if (!$state) return '';

                        if (strlen($state) === 11) {
                            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $state);
                        }

                        return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $state);
                    })
                    ->toggleable(),

                Tables\Columns\IconColumn::make('pescador_artesanal')
                    ->label('Pescador')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('renda_total')
                    ->label('Renda Total')
                    ->state(fn (Cliente $record): float => $record->getRendaTotal())
                    ->money('BRL')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('ativo')
                    ->label('Ativo')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data de Cadastro')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado_civil')
                    ->label('Estado Civil')
                    ->options(Cliente::getEstadosCivis()),

                Tables\Filters\SelectFilter::make('municipio')
                    ->label('Município')
                    ->options(fn (): array => Cliente::distinct()->pluck('municipio', 'municipio')->toArray()),

                Tables\Filters\SelectFilter::make('uf')
                    ->label('UF')
                    ->options(fn (): array => Cliente::distinct()->pluck('uf', 'uf')->toArray()),

                Tables\Filters\TernaryFilter::make('pescador_artesanal')
                    ->label('Pescador Artesanal'),

                Tables\Filters\TernaryFilter::make('ativo')
                    ->label('Cliente Ativo'),
            ])
            ->actions([

               ...ClienteTableActions::getDocumentActions(),

            ])
            ->bulkActions([
                    ...ClienteTableActions::getBulkActions(),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListClientes::route('/'),
            'create' => Pages\CreateCliente::route('/create'),
           // 'view' => Pages\ViewCliente::route('/{record}'),
            'edit' => Pages\EditCliente::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['estado_civil']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['nome_completo', 'cpf', 'municipio'];
    }

    public static function getGlobalSearchResultDetails(Model|\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'CPF' => $record->getCpfFormatado(),
            'Município' => $record->municipio . '/' . $record->uf,
            'Estado Civil' => $record->estado_civil,
        ];
    }
}
