<?php

namespace Armincms\Taggable\Gutenberg\Templates; 

use Zareismail\Gutenberg\Template; 
use Zareismail\Gutenberg\Variable;

class SingleTag extends Template 
{       
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

            Variable::make('contents', __('Rendered tag contents')),

            Variable::make('links', __('Rendered tag pagination links')),
        ];
    } 
}
