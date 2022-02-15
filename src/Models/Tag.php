<?php

namespace Armincms\Taggable\Models;
 
use Armincms\Contract\Concerns\Sluggable;
use Armincms\Contract\Concerns\InteractsWithFragments;
use Armincms\Contract\Concerns\InteractsWithUri;
use Armincms\Contract\Concerns\InteractsWithWidgets; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;  

class Tag extends Model
{   
    use InteractsWithFragments; 
    use InteractsWithUri; 
    use InteractsWithWidgets; 
    use Sluggable; 

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false; 

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return app()->make(\Armincms\Taggable\TagFactory::class);
    } 

    /**
     * Get the corresponding cypress fragment.
     * 
     * @return 
     */
    public function cypressFragment(): string
    {
        return \Armincms\Taggable\Cypress\Fragments\Tag::class;
    } 
    
    /**
     * Serialize the model for pass into the client view.
     *
     * @param Zareismail\Cypress\Request\CypressRequest
     * @return array
     */
    public function serializeForWidget($request): array
    { 
        return array_merge($this->toArray(), [ 
            'url'   => $this->getUrl($request) ?? collect($this->url())->map->url->first(),
        ]);
    }  
}
