<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    /** @use HasFactory<\Database\Factories\AwardFactory> */
    use HasFactory;

    protected $fillable = ['activity_category_id', 'level_id', 'name', 'point'];

    public function activities()
    {
        return $this->hasMany(Activity::class, 'award_id');
    }

    public function category()
    {
        return $this->belongsTo(ActivityCategory::class, 'activity_category_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}
