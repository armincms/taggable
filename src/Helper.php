<?php

namespace Armincms\Taggable;
 
use Illuminate\Http\Request; 
use Armincms\Contracts\HasLayout;   
use Armincms\Helpers\{SharedResource, Common};    

class Helper
{   
    /**
     * Get the categorizable resources available for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public static function availableResources(Request $request)
    {
        return SharedResource::availableResources($request, Contracts\Taggable::class);
    } 

    /**
     * Get meta data information about all resources for client side consumption.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public static function resourceInformation(Request $request)
    { 
        return SharedResource::resourceInformation($request, Contracts\Taggable::class);
    }
}
