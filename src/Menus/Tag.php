<?php

namespace Armincms\Taggable\Menus;

use Armincms\Contract\Menus\MenuItemType;
use Armincms\Taggable\Cypress\Fragments\Tag as Fragment;
use Laravel\Nova\Fields\Select;
use Zareismail\Gutenberg\Gutenberg;

class Tag extends MenuItemType
{
    /**
     * Get the resource name.
     *
     * @return string
     */
    public static function resourceName(): string
    {
        return \Armincms\Taggable\Nova\Tag::class;
    }

    /**
     * Get the resource key name.
     *
     * @return string
     */
    public static function getKeyName()
    {
        return fn ($resource) => $resource->getKey();
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @return array An array of fields.
     */
    public static function getFields(): array
    {
        return array_merge([
            Select::make(__('Target Fragment'), 'fragment')
                ->options(fn () => Gutenberg::cachedFragments()->where('fragment', Fragment::class)->keyBy->getKey()->map->name)
                ->required()
                ->rules('required'),
        ], parent::getFields());
    }

    /**
     * Get the rules for the resource.
     *
     * @return array A key-value map of attributes and rules.
     */
    public static function getRules(): array
    {
        return [
            'data->fragment' => 'required',
        ];
    }

    /**
     * Get the value of the link visible to the front-end.
     *
     * Can be anything. It is up to you how you will handle parsing it.
     *
     * This will only be called when using the nova_get_menu()
     * and nova_get_menus() helpers or when you call formatForAPI()
     * on the Menu model.
     *
     * @param $value The key from options list that was selected.
     * @param $data The data from item fields.
     * @param $locale
     * @return any
     */
    public static function getValue($value, ?array $data, $locale)
    {
        if (is_null($fragment = Gutenberg::cachedFragments()->find($data['fragment']))) {
            return $value;
        }

        if (is_null($page = static::findTag($value))) {
            return $value;
        }

        return $fragment->getUrl($page->getUri());
    }

    /**
     * Find the page with the given key.
     *
     * @param  string  $key
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function findTag($key)
    {
        $pages = \Cache::remember(static::class, 60, function () {
            $resourceClass = static::resourceName();

            return $resourceClass::newModel()->get();
        });

        return $pages->find($key);
    }
}
