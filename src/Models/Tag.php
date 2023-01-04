<?php

namespace Armincms\Taggable\Models;

use Armincms\Contract\Concerns\InteractsWithFragments;
use Armincms\Contract\Concerns\InteractsWithUri;
use Armincms\Contract\Concerns\InteractsWithWidgets;
use Armincms\Contract\Concerns\Sluggable;
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
            'url' => $this->getUrl($request) ?? collect($this->url())->map->url->first(),
        ]);
    }

    /**
     * Prase the given string to get related models.
     *
     * @param $tags [array|string]
     * @param array<static>
     */
    public static function parseTags(string|array $tags): array
    {
        return collect(is_string($tags) ? json_decode($tags, true) : $tags)->filter()->map(fn ($tag) => static::createByName($tag))->all();
    }

    /**
     * Prase the given string to get realted model or create it.
     *
     * @param $tags [array|string]
     * @param array<static>
     */
    public function createByName(string $name)
    {
        return static::unguarded(fn () => static::firstOrCreate(compact('name')));
    }
}
