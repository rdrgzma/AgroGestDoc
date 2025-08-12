<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ufpa extends Model
{
    protected $fillable = [
        'person_id',
        'nome_propriedade',
        'area_total',
        'localizacao',
        'matricula',
        'nirf',
        'ccir',
        'car',
        'tipo_posse',
        'created_by',
    ];

    public function pessoa()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function criador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function producoes()
    {
        return $this->hasMany(Producao::class);
    }
}


