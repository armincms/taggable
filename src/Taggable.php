<?php

namespace Armincms\Taggable;
 

trait Taggable  
{ 
	public function tags()
	{
		return $this->morphToMany(Tag::class, 'taggable', 'tags_taggable');
	}
}
