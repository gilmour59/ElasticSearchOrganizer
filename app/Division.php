<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    public $timestamps = false;
    //
    public function archivefiles(){
        return $this->hasMany('App\ArchiveFile');
    }
}
