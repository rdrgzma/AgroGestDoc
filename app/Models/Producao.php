<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Producao extends Model
{
    protected $table = 'producoes';
    protected $fillable = ['ufpa_id', 'produto', 'categoria', 'valor_anual'];

    public function ufpa(): BelongsTo {
        return $this->belongsTo(Ufpa::class);
    }
}

