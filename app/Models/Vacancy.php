<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'job_title',
        'description',
        'contracting_manager',
        'number_of_vacancies',
        'active',
        'user_id', // Llave foránea
    ];

    // Relación con la tabla "users"
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
