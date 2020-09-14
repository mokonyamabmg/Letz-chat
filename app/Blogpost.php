<?php

namespace App;

use App\Blogpost;
use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blogpost extends Model
{

    use SoftDeletes;

    protected $table = 'blogposts';


    protected $fillable = [
        'title', 'content', 'photo', 'user_id'
    ];

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function likes()
    {
        return $this->hasMany('App\Like');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function image()
    {
        return $this->hasOne('App\Image');
    }

    public function scopeMostCommented(Builder $query)
    {
        return $query->withCount('comments')->orderBy('comments_count', 'desc');
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new LatestScope);

        static::deleting(function (Blogpost $blogpost) {

            $blogpost->comments()->delete();
            $blogpost->likes()->delete();

        });
    }
}
