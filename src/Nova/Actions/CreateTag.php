<?php

namespace Armincms\Taggable\Nova\Actions;

use Armincms\Taggable\Nova\Tag;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;

class CreateTag extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        \DB::transaction(function() use ($fields) { 
            Tag::newModel()->forceFill(['name' => $fields->get('name')])->save();
        });
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [ 
            Text::make(__('Tag Name'), 'name')
                ->required()
                ->rules('required'),
        ];
    }
}
