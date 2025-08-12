<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model
{
    use HasFactory;
    protected $table = 'people';
    protected $fillable = [
        'nome', 'cpf', 'rg', 'data_nascimento', 'naturalidade', 'estado_civil',
        'nome_mae', 'email', 'telefone', 'endereco', 'bairro', 'cidade', 'uf', 'cep',
        'endereco_correspondencia', 'created_by', 'updated_by'
    ];

    public function ufpas(): HasMany {
        return $this->hasMany(Ufpa::class);
    }

    public function documentos(): HasMany {
        return $this->hasMany(DocumentoGerado::class);
    }

    public function criadoPor()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

