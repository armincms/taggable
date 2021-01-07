<?php

namespace Armincms\Taggable\Contracts;


interface Taggable
{
	/**
	 * Query the related tags.
	 * 
	 * @return \Illuminate\Database\Eloqenut\Relations\BelongsToMany
	 */
	public function tags();
}