<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentoGerado extends Model
{
    protected $table = 'documentos_gerados';
    protected $fillable = ['person_id', 'tipo', 'formato', 'arquivo', 'created_by'];

    public function person(): BelongsTo {
        return $this->belongsTo(Person::class);
    }
}

