<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|\App\Models\Photo[] $photos
 */
class Album extends Model
{
    use HasFactory;

    protected $guarded = [];
    // Aditional fields
    protected $appends = array('photoCount', 'coverPhoto');

    public function photos() : BelongsToMany
    {
        return $this->belongsToMany(Photo::class);
    }

    // Returns how many photos the album have
    public function getPhotoCountAttribute()
    {
        return $this->photos()->count();
    }

    // Returns first photo to use as the album cover
    public function getCoverPhotoAttribute()
    {
        return $this->photos()->first();
    }
}
