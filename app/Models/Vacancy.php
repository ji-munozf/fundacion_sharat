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
        'user_id', // Llave foránea hacia la tabla "users"
        'institution_id', // Llave foránea hacia la tabla "institutions"
    ];

    // Relación con la tabla "users"
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con la tabla "institutions"
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function applications()
    {
        return $this->hasMany(Postulation::class);
    }
}
