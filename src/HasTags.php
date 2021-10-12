<?php

namespace Armincms\Taggable; 

trait HasTags
{  
    /**
     * Query related tags.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->morphToMany(Models\Tag::class, 'taggable', 'tags_taggable');
    }

    /**
     * Add related tags with attribute.
     * 
     * @param void
     */
    public function setQueuedTagsAttribute($tags)
    {
        if (is_string($tags)) {
            $tags = json_decode($tags, true);
        }

        static::saved(function($model) use ($tags) {
            $tags = collect($tags)->map(function($name) {
                return Models\Tag::unguarded(function() use ($name) {
                    return Models\Tag::firstOrCreate(compact('name'))->getKey();
                }); 
            });

            $model->tags()->sync($tags->all());
        });
    }

    /**
     * Add related tags with attribute.
     * 
     * @param void
     */
    public function getQueuedTagsAttribute($tags)
    {
        return $this->tags->map->name;
    }
}
