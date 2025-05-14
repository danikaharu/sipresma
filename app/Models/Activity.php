<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    /** @use HasFactory<\Database\Factories\ActivityFactory> */
    use HasFactory;

    protected $fillable = ['student_id', 'activity_category_id', 'level_id', 'award_id', 'award_type', 'name', 'place', 'start_date', 'end_date', 'photo', 'file', 'description', 'status'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function category()
    {
        return $this->belongsTo(ActivityCategory::class, 'activity_category_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function award()
    {
        return $this->belongsTo(Award::class, 'award_id');
    }

    public function award_type()
    {
        if ($this->award_type == 1) {
            return 'Akademik';
        } else {
            return 'Non Akademik';
        }
    }

    public function status()
    {
        if ($this->status == 0) {
            return 'Belum Diverifikasi';
        } elseif ($this->status == 1) {
            return 'Diterima';
        } else {
            return 'Ditolak';
        }
    }
}
