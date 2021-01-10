<?php

namespace Armincms\Taggable;

use Illuminate\Http\Request; 
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

    const TRANSLATION_MODEL = Translation::class;

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

    /**
     * Prepare the resource for JSON serialization.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function serializeForDetail(Request $request)
    {
        return [
            'name' => $this->tag,
            'logo' => $this->getLogo(),
            'banner' => $this->getBanner(), 
        ];
    }

    /**
     * Retruns the Banner images.
     * 
     * @return array
     */
    public function getBanner()
    {
        return $this->getConversions($this->getFirstMedia('banner'), ['common-main', 'common-thumbnail']);
    }

    /**
     * Retruns the Logo images.
     * 
     * @return array
     */
    public function getLogo()
    {
        return $this->getConversions($this->getFirstMedia('logo'), ['thumbnail']);
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if($resource = Helper::resourceInformation(app('request'))->where('key', $method)->first()) {
            return $this->morphedByMany($resource['model'], 'taggable', 'tags_taggable');
        }

        return parent::__call($method, $parameters);
    }
}
