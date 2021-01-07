<?php

namespace Armincms\Taggable;

use Illuminate\Database\Eloquent\{Model, SoftDeletes}; 
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Armincms\Concerns\{HasConfig, HasMediaTrait, Authorization, InteractsWithLayouts};  
use Armincms\Targomaan\Concerns\InteractsWithTargomaan;
use Armincms\Targomaan\Contracts\Translatable; 
use Armincms\Contracts\Authorizable;  

class Tag extends Model implements Translatable, HasMedia, Authorizable 
{
    use InteractsWithTargomaan, SoftDeletes, HasMediaTrait, Authorization, HasConfig, InteractsWithLayouts; 
    
    const TRANSLATION_TABLE = 'tags_translations';

    const LOCALE_KEY = 'language';

    protected $medias = [
        'banner' => [  
            'disk'  => 'armin.image',
            'conversions' => [
                'common'
            ]
        ], 

        'logo' => [  
            'disk'  => 'armin.image',
            'conversions' => [
                'common'
            ]
        ], 

        'app_banner' => [  
            'disk'  => 'armin.image',
            'conversions' => [
                'common'
            ]
        ], 

        'app_logo' => [  
            'disk'  => 'armin.image',
            'conversions' => [
                'common'
            ]
        ], 
    ]; 

    /**
     * Driver name of the targomaan.
     * 
     * @return [type] [description]
     */
    public function translator(): string
    {
        return 'layeric';
    }
}
