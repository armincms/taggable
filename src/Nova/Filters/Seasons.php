<?php

namespace Armincms\Alhazen\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use Armincms\Alhazen\AlhazenSeries; 

class Seasons extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where("detail->season", $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    { 
        return AlhazenSeries::with('episodes')->findOrFail($request->viaResourceId)
                        ->episodes  
                        ->mapWithKeys(function($episode) {
                            $season = data_get($episode, 'detail.season');

                            return [__("Season :season", compact('season')) => $season];
                        });  
    }
}
