<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producao extends Model
{
    protected $table = 'producoes';
    protected $fillable = [
        'ufpa_id',
        'produto',
        'quantidade',
        'unidade',
        'ano',
        'created_by',
    ];

    public function ufpa()
    {
        return $this->belongsTo(Ufpa::class);
    }

    public function criador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}


