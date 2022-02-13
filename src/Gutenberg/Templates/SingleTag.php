<?php

namespace Armincms\Taggable\Gutenberg\Templates; 

use Zareismail\Gutenberg\Template; 
use Zareismail\Gutenberg\Variable;

class SingleTag extends Template 
{       
     /**
     * The logical group associated with the template.
     *
     * @var string
     */
    public static $group = 'Tags'; 

    /**
     * Register the given variables.
     * 
     * @return array
     */
    public static function variables(): array
    {
        return [ 
            Variable::make('id', __('Tag Id')),

            Variable::make('name', __('Tag Name')),

            Variable::make('url', __('Tag URL')),

            Variable::make('hits', __('Tag hits')), 

            Variable::make('items', __('Rendered tagged items')),

            Variable::make('pagination', __('Rendered tag pagination links')),
        ];
    } 
}
