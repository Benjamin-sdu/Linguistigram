<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model
{
    use HasFactory;
    
    protected $guarded=[];




    function media () : MorphMany {
        return $this->morphMany(Media::class,'mediable');
    }


    function user() : BelongsTo {

        return $this->belongsTo(User::class);
        
    }


    function comments() : MorphMany {

        return $this->morphMany(Comment::class,'commentable')->with('replies');
        
    }




}
