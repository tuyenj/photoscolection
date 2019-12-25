<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Storage;

/**
 * App\Photo
 *
 * @property int $id
 * @property int $user_id
 * @property string $filename
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read mixed $image_path
 * @method static Builder|Photo newModelQuery()
 * @method static Builder|Photo newQuery()
 * @method static Builder|Photo query()
 * @method static Builder|Photo whereCreatedAt($value)
 * @method static Builder|Photo whereFilename($value)
 * @method static Builder|Photo whereId($value)
 * @method static Builder|Photo whereUpdatedAt($value)
 * @method static Builder|Photo whereUserId($value)
 * @mixin Eloquent
 */
class Photo extends Model
{
    protected $appends = ['image_path'];
    protected $hidden = ['user_id', 'filename', 'created_at', 'updated_at'];
    protected $perPage = 9;

    /**
     * @return BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }

    /**
     * @return string
     */
    public function getImagePathAttribute()
    {
        return Storage::cloud()->url($this->filename);
    }
}
