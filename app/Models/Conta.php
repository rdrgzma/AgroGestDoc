<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conta extends Model
{
    protected $fillable = [
        'person_id', 'tipo', 'descricao', 'valor', 'vencimento',
        'status', 'forma_pagamento', 'created_by', 'updated_by','cliente_id'
    ];

    public function person(): BelongsTo {
        return $this->belongsTo(Person::class);
    }
    public function cliente(): BelongsTo {
        return $this->belongsTo(Cliente::class);
    }
}

