<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{

    public function blogpost()
    {
        return $this->belongsTo('App\Blogpost');
    }
}
