<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome_completo',
        'cpf',
        'rg',
        'orgao_expedidor',
        'data_nascimento',
        'local_nascimento',
        'estado_civil',
        'nome_mae',
        'apelido',
        'cor_raca',
        'sexo',
        'escolaridade',
        'telefone',
        'email',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'municipio',
        'uf',
        'cep',
        'endereco_correspondencia_diferente',
        'endereco_correspondencia',
        'numero_correspondencia',
        'complemento_correspondencia',
        'bairro_correspondencia',
        'municipio_correspondencia',
        'uf_correspondencia',
        'cep_correspondencia',
        'nome_conjuge',
        'cpf_conjuge',
        'rg_conjuge',
        'data_nascimento_conjuge',
        'local_nascimento_conjuge',
        'apelido_conjuge',
        'cor_raca_conjuge',
        'escolaridade_conjuge',
        'projeto_assentamento',
        'codigo_sipra',
        'beneficiario_credito_fundiario',
        'gestor_ufpa',
        'mao_obra_ufpa',
        'assentado_reforma_agraria',
        'area_indigena_quilombola',
        'pescador_artesanal',
        'tipo_area',
        'medida_area',
        'area_total',
        'area_explorada',
        'coordenada_latitude',
        'coordenada_longitude',
        'renda_bruta_estabelecimento',
        'renda_fora_estabelecimento',
        'beneficios_sociais',
        'pessoas_familia_residentes',
        'pessoas_familia_trabalham',
        'empregados_permanentes',
        'atividade_principal',
        'outras_atividades',
        'periodo_atividade_inicio',
        'periodo_atividade_fim',
        'condicao_imovel',
        'situacao_atividade',
        'banco_agencia',
        'conta_corrente',
        'pessoa_exposta_politicamente',
        'observacoes',
        'ativo',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'data_nascimento_conjuge' => 'date',
        'periodo_atividade_inicio' => 'date',
        'periodo_atividade_fim' => 'date',
        'beneficiario_credito_fundiario' => 'boolean',
        'gestor_ufpa' => 'boolean',
        'mao_obra_ufpa' => 'boolean',
        'assentado_reforma_agraria' => 'boolean',
        'area_indigena_quilombola' => 'boolean',
        'pescador_artesanal' => 'boolean',
        'endereco_correspondencia_diferente' => 'boolean',
        'pessoa_exposta_politicamente' => 'boolean',
        'ativo' => 'boolean',
        'area_total' => 'decimal:2',
        'area_explorada' => 'decimal:2',
        'renda_bruta_estabelecimento' => 'decimal:2',
        'renda_fora_estabelecimento' => 'decimal:2',
        'beneficios_sociais' => 'decimal:2',
        'pessoas_familia_residentes' => 'integer',
        'pessoas_familia_trabalham' => 'integer',
        'empregados_permanentes' => 'integer',
    ];

    // Accessors & Mutators

    protected function cpf(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value,
            set: fn (?string $value) => $value ? preg_replace('/[^0-9]/', '', $value) : null,
        );
    }

    protected function cpfConjuge(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value,
            set: fn (?string $value) => $value ? preg_replace('/[^0-9]/', '', $value) : null,
        );
    }

    protected function telefone(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value,
            set: fn (?string $value) => $value ? preg_replace('/[^0-9]/', '', $value) : null,
        );
    }

    protected function cep(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value,
            set: fn (?string $value) => $value ? preg_replace('/[^0-9]/', '', $value) : null,
        );
    }

    protected function cepCorrespondencia(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value,
            set: fn (?string $value) => $value ? preg_replace('/[^0-9]/', '', $value) : null,
        );
    }

    // Métodos auxiliares

    public function isCasado(): bool
    {
        return in_array($this->estado_civil, ['casado', 'união estável']);
    }

    public function temRenda(): bool
    {
        return ($this->renda_bruta_estabelecimento ?? 0) > 0 ||
            ($this->renda_fora_estabelecimento ?? 0) > 0;
    }

    public function getIdade(): ?int
    {
        return $this->data_nascimento ?
            Carbon::parse($this->data_nascimento)->age : null;
    }

    public function getIdadeConjuge(): ?int
    {
        return $this->data_nascimento_conjuge ?
            Carbon::parse($this->data_nascimento_conjuge)->age : null;
    }

    public function getRendaTotal(): float
    {
        return ($this->renda_bruta_estabelecimento ?? 0) +
            ($this->renda_fora_estabelecimento ?? 0) +
            ($this->beneficios_sociais ?? 0);
    }

    public function getEnderecoCompleto(): string
    {
        $endereco = $this->endereco;

        if ($this->numero) {
            $endereco .= ', ' . $this->numero;
        }

        if ($this->complemento) {
            $endereco .= ', ' . $this->complemento;
        }

        if ($this->bairro) {
            $endereco .= ' - ' . $this->bairro;
        }

        $endereco .= ' - ' . $this->municipio . '/' . $this->uf;

        if ($this->cep) {
            $endereco .= ' - CEP: ' . $this->getCepFormatado();
        }

        return $endereco;
    }

    public function getEnderecoCorrespondenciaCompleto(): ?string
    {
        if (!$this->endereco_correspondencia_diferente) {
            return null;
        }

        $endereco = $this->endereco_correspondencia;

        if ($this->numero_correspondencia) {
            $endereco .= ', ' . $this->numero_correspondencia;
        }

        if ($this->complemento_correspondencia) {
            $endereco .= ', ' . $this->complemento_correspondencia;
        }

        if ($this->bairro_correspondencia) {
            $endereco .= ' - ' . $this->bairro_correspondencia;
        }

        $endereco .= ' - ' . $this->municipio_correspondencia . '/' . $this->uf_correspondencia;

        if ($this->cep_correspondencia) {
            $endereco .= ' - CEP: ' . $this->getCepCorrespondenciaFormatado();
        }

        return $endereco;
    }

    public function getCpfFormatado(): string
    {
        return $this->cpf ? preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $this->cpf) : '';
    }

    public function getCpfConjugeFormatado(): string
    {
        return $this->cpf_conjuge ? preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $this->cpf_conjuge) : '';
    }

    public function getTelefoneFormatado(): string
    {
        if (!$this->telefone) return '';

        if (strlen($this->telefone) === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $this->telefone);
        }

        return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $this->telefone);
    }

    public function getCepFormatado(): string
    {
        return $this->cep ? preg_replace('/(\d{5})(\d{3})/', '$1-$2', $this->cep) : '';
    }

    public function getCepCorrespondenciaFormatado(): string
    {
        return $this->cep_correspondencia ? preg_replace('/(\d{5})(\d{3})/', '$1-$2', $this->cep_correspondencia) : '';
    }

    public function getNomeAbreviado(): string
    {
        $partes = explode(' ', $this->nome_completo);

        if (count($partes) <= 2) {
            return $this->nome_completo;
        }

        return $partes[0] . ' ' . end($partes);
    }

    public function getCoordenadasFormatadas(): ?string
    {
        if (!$this->coordenada_latitude || !$this->coordenada_longitude) {
            return null;
        }

        return "Lat: {$this->coordenada_latitude}, Long: {$this->coordenada_longitude}";
    }

    // Scopes

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopeCasados($query)
    {
        return $query->whereIn('estado_civil', ['casado', 'união estável']);
    }

    public function scopeSolteiros($query)
    {
        return $query->where('estado_civil', 'solteiro');
    }

    public function scopeComRenda($query)
    {
        return $query->where(function ($q) {
            $q->where('renda_bruta_estabelecimento', '>', 0)
                ->orWhere('renda_fora_estabelecimento', '>', 0);
        });
    }

    public function scopePorMunicipio($query, string $municipio)
    {
        return $query->where('municipio', $municipio);
    }

    public function scopePorUf($query, string $uf)
    {
        return $query->where('uf', $uf);
    }

    public function scopePescadoresArtesanais($query)
    {
        return $query->where('pescador_artesanal', true);
    }

    // Métodos estáticos

    public static function getEstadosCivis(): array
    {
        return [
            'solteiro' => 'Solteiro(a)',
            'casado' => 'Casado(a)',
            'união estável' => 'União Estável',
            'divorciado' => 'Divorciado(a)',
            'viúvo' => 'Viúvo(a)',
        ];
    }

    public static function getCoresRaca(): array
    {
        return [
            'branco' => 'Branco',
            'pardo' => 'Pardo',
            'preto' => 'Preto',
            'amarelo' => 'Amarelo',
            'indígena' => 'Indígena',
        ];
    }

    public static function getCondicoesImovel(): array
    {
        return [
            'Proprietário' => 'Proprietário',
            'Possuidor' => 'Possuidor',
            'Comodatário' => 'Comodatário',
            'Arrendatário' => 'Arrendatário',
            'Parceiro' => 'Parceiro',
            'Meeiro' => 'Meeiro',
            'Usufrutuário' => 'Usufrutuário',
            'Condômino' => 'Condômino',
            'Posseiro' => 'Posseiro',
            'Assentado' => 'Assentado',
            'Acampado' => 'Acampado',
        ];
    }

    public static function getTiposArea(): array
    {
        return [
            'Terra' => 'Terra',
            'Lâmina de água' => 'Lâmina de água',
            'Tanque' => 'Tanque',
        ];
    }
}
