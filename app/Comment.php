<?php

namespace App;

use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'content'
    ];

    public function blogpost()
    {
        return $this->belongsTo('App\Blogpost');
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new LatestScope);

    }
}
