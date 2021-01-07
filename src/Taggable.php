<?php

namespace Armincms\Taggable;

use Illuminate\Database\Eloquent\Relations\MorphPivot;  

class Taggable extends MorphPivot 
{   
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tags_taggable';
}
