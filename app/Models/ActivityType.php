<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    /** @use HasFactory<\Database\Factories\ActivityTypeFactory> */
    use HasFactory;

    protected $fillable = ['activity_category_id', 'name'];

    public function category()
    {
        return $this->belongsTo(ActivityCategory::class, 'activity_category_id');
    }
}
