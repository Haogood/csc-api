<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainPage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'filename', 'filesize', 'path', 'order'
    ];

}
