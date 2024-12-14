<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;

    protected $fillable = ['student_number', 'name', 'program', 'enrollment_year', 'birth_date', 'address'];

    public function activities()
    {
        return $this->hasMany(Activity::class, 'student_id');
    }

    public function program()
    {
        if ($this->program == 1) {
            return 'Sistem Informasi';
        } else {
            return 'Pendidikan Teknologi Informasi';
        }
    }
}
