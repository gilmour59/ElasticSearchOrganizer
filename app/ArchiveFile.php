<?php

namespace App;

use ScoutElastic\Searchable;
use Illuminate\Database\Eloquent\Model;

class ArchiveFile extends Model
{
    use Searchable;

    protected $indexConfigurator = ArchiveFileIndexConfigurator::class;

    public function division(){
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

    protected $mapping = [
        'properties' => [
            'id' => [
                'type' => 'integer',
            ],
            'date' => [
                'type' => 'date',
            ],
            'content' => [
                'type' => 'text',
                'analyzer' => 'standard'
            ],
            'file_name' => [
                'type' => 'text',
                'analyzer' => 'standard'
            ],
            'division_id' => [
                'type' => 'integer',
            ]
        ]
    ];
}
