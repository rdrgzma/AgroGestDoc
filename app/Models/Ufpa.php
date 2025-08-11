<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ufpa extends Model
{
    protected $fillable = [
        'person_id', 'assentamento', 'municipio', 'estado',
        'latitude', 'longitude', 'area_total', 'created_by', 'updated_by'
    ];

    public function person(): BelongsTo {
        return $this->belongsTo(Person::class);
    }

    public function producoes(): HasMany {
        return $this->hasMany(Producao::class);
    }

    public function familiares(): HasMany {
        return $this->hasMany(Familiar::class);
    }
}

