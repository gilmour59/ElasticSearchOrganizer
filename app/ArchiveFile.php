<?php

namespace App;

use ScoutElastic\Searchable;
use Illuminate\Database\Eloquent\Model;

class ArchiveFile extends Model
{
    use Searchable;

    protected $indexConfigurator = ArchiveFileIndexConfigurator::class;

    public function category(){
        return $this->belongsTo('App\Division');
    }
    
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'content' => $this->content,
            'file_name' => $this->file_name,
        ];
    }
}
