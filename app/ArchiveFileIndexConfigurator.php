<?php

namespace App;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class ArchiveFileIndexConfigurator extends IndexConfigurator
{
    use Migratable;

    protected $name = 'archivefile_index';  

    /**
     * @var array
     */
    protected $settings = [
        //
    ];
}