<?php

namespace Armincms\Taggable\Nova;
 
use Armincms\Nova\Resource ;  
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Flexible;
use Eminiarts\Tabs\Tabs;
use OwenMelbz\RadioField\RadioButton;

abstract class Tag extends Resource
{    
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Armincms\\Taggable\\Tag';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'tag'; 

    /**
     * The columns that should be searched in the translation table.
     *
     * @var array
     */
    public static $searchTranslations = [
        'tag'
    ];  
    
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [ 
            $this->resourceField(__("Tag"), 'tag'),  

            $this->panel(__("Tag display settings"), $this->tab(function($tab) use ($request) { 
                foreach ($this->taggables() as $taggable) {
                    if($fields = $this->taggableFields($request, $taggable)) {
                        $tab->group($taggable::label(), $fields);
                    }  
                }  
            })->toArray()), 
        ];
    }

    public function taggableFields(Request $request, $taggable)
    { 
        return collect(static::screens())->map(function($screenName, $screen) use ($request, $taggable) { 

            if(! $this->shouldIgnoreScreen($request, $taggable, $screen)) { 
                $fields =  $this->prepareTaggableFields(
                    $taggable, $this->prepareScreenFields($screen, $taggable::fields($request))
                );

                $toggle = $this->screenToggler($screenName, "{$taggable::configKey()}.{$screen}", [
                    0 => collect($fields)->map->attribute->filter()
                ]);
                     

                return collect($fields)
                        ->flatten()
                        ->prepend($toggle)
                        ->prepend($this->heading($screenName)->onlyOnDetail())
                        ->each(function($field) use ($taggable, $screen)  { 
                            $field->canSee(function($request) use ($taggable, $screen) { 
                                if($request->editing == false) { 
                                    return data_get(
                                        $request->findModelQuery()->first(), "config.{$taggable::configKey()}.{$screen}"
                                    );
                                }  

                                return true; 
                            });   
                        }); 
            } 

            return $this->prepareTaggableFields($taggable, [
                Text::make($screenName, $screen)->fillUsing(function() {
                    return [];
                }),
            ]);
        })->filter()->flatten()->toArray();
    }

    public static function screens()
    {
        return [
            'desktop' => __('Desktop'), 
            'mobile'  => __('Mobile'), 
            'tablet'  => __('Tablet')
        ];
    } 

    public function shouldIgnoreScreen(Request $request, $taggable, $screen)
    {
        return $request->editing &&
               $request->exists($taggable::configKey()."_{$screen}") &&
               (int) $request->get($taggable::configKey()."_{$screen}") == 0;
    } 

    public function prepareTaggableFields($taggable, $fields)
    { 
        return $this->configField([
                    $this->jsonField($taggable::configKey(), $fields)
                ]) 
                ->saveHistory()
                ->hideFromIndex()
                ->toArray();
    }

    public function prepareScreenFields($screen, $fields)
    {
        return [
            $this->jsonField($screen, $fields)
        ];
    }

    public function screenToggler($name, $attribute, $toggles = [])
    {
        return RadioButton::make($name, $attribute)
                    ->options([__("Default"), __("Custom")])
                    ->toggle($toggles)
                    ->default(0)
                    ->marginBetween()
                    ->onlyOnForms()
                    ->fillUsing(function() { })
                    ->resolveUsing(function($value, $taggable, $attribute) {  
                        return data_get($taggable->config, $attribute) ? 1 : 0;
                    });
    }

    abstract public function taggables() : array;
}
