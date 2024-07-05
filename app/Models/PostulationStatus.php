<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostulationStatus extends Model
{
    use HasFactory;

    // La tabla asociada con el modelo
    protected $table = 'postulation_status';

    // Los atributos que son asignables en masa.
    protected $fillable = [
        'status',
        'reasons',
        'postulation_id',
    ];

    // Definir la relación con el modelo Postulation
    public function postulation()
    {
        return $this->belongsTo(Postulation::class);
    }

}
