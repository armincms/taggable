<?php

namespace Armincms\Taggable\Concerns;


trait InteractsWithTags
{
	/**
	 * Query the related tags.
	 * 
	 * @return \Illuminate\Database\Eloqenut\Relations\BelongsToMany
	 */
	public function tags()
	{
		return $this->morphToMany(\Armincms\Taggable\Tag::class, 'taggable', 'tags_taggable')
					->using(\Armincms\Taggable\Taggable::class);
	}
}