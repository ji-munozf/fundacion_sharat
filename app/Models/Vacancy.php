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
        'gross_salary',
        'active',
        'is_eliminated',
        'is_eliminated_postulant',
        'user_id',
        'institution_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function applications()
    {
        return $this->hasMany(Postulation::class);
    }
}
