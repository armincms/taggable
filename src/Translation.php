<?php

namespace Armincms\Taggable; 
 
use Illuminate\Database\Eloquent\Relations\Pivot;
use Cviebrock\EloquentSluggable\Sluggable;
use Core\HttpSite\Concerns\{IntractsWithSite, HasPermalink}; 
use Core\HttpSite\Component; 

class Translation extends Pivot  
{ 
	use Sluggable, HasPermalink, IntractsWithSite;   

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false; 

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = []; 

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'tag'
            ]
        ];  
    }  

    public function component() : Component
    { 
        return new Components\Tag;
    }
}