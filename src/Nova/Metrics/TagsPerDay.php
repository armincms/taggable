<?php

namespace Armincms\Taggable\Nova\Metrics;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;

class TagsPerDay extends Trend
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->countByDays($request, \Armincms\Taggable\Tag::class);
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            30  => __(':number Days', ['number' => 30]),
            60  => __(':number Days', ['number' => 60]),
            90  => __(':number Days', ['number' => 90]), 
            180 => __(':number Days', ['number' => 180]), 
            360 => __(':number Days', ['number' => 360]), 
        ];
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'taggable-tags-per-day';
    }
}
