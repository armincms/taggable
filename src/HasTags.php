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
        static::saved(fn ($model) => $model->tags()->sync(Models\Tag::parseTags($tags)));
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
