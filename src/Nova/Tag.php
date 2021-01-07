<?php

namespace Armincms\Taggable\Nova;
  
use Illuminate\Http\Request;
use Laravel\Nova\Panel; 
use Laravel\Nova\Fields\{Text, Select, BooleanGroup}; 
use Whitecube\NovaFlexibleContent\Flexible;  
use Armincms\Nova\Fields\Images; 
use Armincms\Fields\Targomaan;
use Zareismail\Fields\Complex;
use Armincms\Taggable\Helper;
use Armincms\Nova\Resource;  

class Tag extends Resource
{    
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Taggable\Tag::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'tag';  

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Taxonomies';

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    { 
        return [    
            Targomaan::make([
                
                Text::make(__('Tag Name'), 'tag')
                    ->required()
                    ->rules('required'),

                Text::make(__('Url Slug'), 'slug') 
                    ->nullable()
                    ->hideFromIndex()
                    ->help(__('Caution: cleaning the input causes rebuild it. This string used in url address.')), 
            ]), 

            Complex::make(__('Images'), [$this, 'imageFields']),   

            new Panel(__('Advanced'), [  

                Select::make(__('Display Layout'), 'config->layout')
                    ->options(collect(static::newModel()->singleLayouts())->map->label())
                    ->displayUsingLabels()
                    ->hideFromIndex(),

                Complex::make(__('Contents Display Layout'), function() use ($request) {
                    return Helper::displayableResources($request)->map(function($resource) {
                            return Select::make(__($resource::label()), 'config->layouts->'.$resource::uriKey())
                                        ->options(collect($resource::newModel()->singleLayouts())->map->label())
                                        ->displayUsingLabels()
                                        ->hideFromIndex();
                    }); 
                }), 

                BooleanGroup::make(__('Display Setting'), 'config->display')
                    ->options($this->displayConfigurations($request)),


                Flexible::make(__('Contents Display Settings'))
                    ->preset(\Armincms\Nova\Flexible\Presets\RelatableDisplayFields::class, [
                        'request'   => $request,
                        'interface' => \Armincms\Taggable\Contracts\Taggable::class, 
                    ]),

            ]), 
        ];
    }   

    /**
     * Return`s array of fields to hnalde iamges.
     * 
     * @return array
     */
    public function imageFields()
    {
        return [  
            Images::make(__('Banner'), 'banner')
                ->conversionOnPreview('common-thumbnail') 
                ->conversionOnDetailView('common-thumbnail') 
                ->conversionOnIndexView('common-thumbnail')
                ->fullSize(),

            Images::make(__('Logo'), 'logo')
                ->conversionOnPreview('common-thumbnail') 
                ->conversionOnDetailView('common-thumbnail') 
                ->conversionOnIndexView('common-thumbnail')
                ->fullSize(),

            Images::make(__('Application Banner'), 'app_banner')
                ->conversionOnPreview('common-thumbnail') 
                ->conversionOnDetailView('common-thumbnail') 
                ->conversionOnIndexView('common-thumbnail')
                ->fullSize(),

            Images::make(__('Application Logo'), 'app_logo')
                ->conversionOnPreview('common-thumbnail') 
                ->conversionOnDetailView('common-thumbnail') 
                ->conversionOnIndexView('common-thumbnail')
                ->fullSize(), 
        ];
    }

    /**
     * Returnc tag display configurations.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request 
     * @return array
     */
    public function displayConfigurations(Request $request)
    {
        return [
            'name' => __('Display the tag name'), 

            'banner' => __('Display the tag banner'),

            'logo' => __('Display the tag logo if possible'), 
        ];
    }  

    /**
     * Determine if the resource should be available for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizeToViewAny(Request $request)
    {
        return true;
    } 

    /**
     * Determine if the resource should be available for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function authorizedToViewAny(Request $request)
    {
        return true;
    }

    /**
     * Get the cards available on the entity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [
            Metrics\NewTags::make(),

            Metrics\TagsPerDay::make(),

            Metrics\TagsPerResource::make(),
        ];
    }
}
