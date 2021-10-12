<?php

namespace Armincms\Taggable\Nova\Fields;

use Superlatif\NovaTagInput\Tags; 

class Tag extends Tags
{   
    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  callable|null  $resolveCallback
     * @return void
     */
    public function __construct($name, callable $resolveCallback = null)
    {
        parent::__construct($name, 'queued_tags', $resolveCallback); 
    }
}
