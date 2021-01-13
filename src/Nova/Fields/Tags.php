<?php

namespace Armincms\Taggable\Nova\Fields;

use Laravel\Nova\Http\Requests\NovaRequest;
use Superlatif\NovaTagInput\Tags as Field; 
use Armincms\Taggable\Tag;

class Tags extends Field
{  
    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|callable|null  $attribute
     * @param  callable|null  $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
    	parent::__construct($name, $attribute, function($tags) {
    		return collect($tags)->pluck('tag')->all();
    	});

    	$this->autocompleteItems(Tag::get()->map->tag->all());
    } 

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  string  $requestAttribute
     * @param  object  $model
     * @param  string  $attribute
     * @return mixed
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
    	return function() use ($request, $requestAttribute, $model) {  
	        $model->tags()->sync($this->getTagIds(
	        	collect(json_decode($request[$requestAttribute] ?? '[]', true))->pluck('text')->all()
	        ));
    	}; 
    } 

    /**
     * Return`s array of tag ids.
     * 
     * @param  array  $items 
     * @return array        
     */
    public function getTagIds(array $items)
    {
    	$tags = Tag::get()->pluck('tag', 'id');

    	$newTags = collect($items)->reject(function($text) use ($tags) {
    		return $tags->contains($text);
    	})->map(function($tag) {
    		$model = tap(new Tag, function($tag) {
    			$tag->forceFill(['config' => []]);
    		});

			$model->setTranslation('tag', $tag);
			$model->save();
    	}); 

    	return Tag::get()->pluck('id', 'tag')->only($items)->values()->all();
    }
}