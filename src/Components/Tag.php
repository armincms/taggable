<?php 
namespace Armincms\Taggable\Components;
 
use Illuminate\Http\Request; 
use Core\Document\Document;
use Core\HttpSite\Component;
use Core\HttpSite\Contracts\Resourceable;
use Core\HttpSite\Concerns\IntractsWithLayout;
use Core\HttpSite\Concerns\IntractsWithResource;
use Armincms\Taggable\Tag as TagModel;
use Armincms\Taggable\Helper;

class Tag extends Component implements Resourceable
{       
	use IntractsWithResource, IntractsWithLayout; 

	/**
	 * Route of Component.
	 * 
	 * @var null
	 */
	protected $route = '{slug}'; 

	public function toHtml(Request $request, Document $docuemnt) : string
	{       
		$tag = TagModel::whereHas('translations', function($query) use ($request) {
			$query->whereUrl($request->relativeUrl());
		})->firstOrFail();
		
		$this->resource($tag);   
		$docuemnt->title($tag->tag);  
		$docuemnt->description($tag->tag);   
		$layout = $tag->getConfig('layout', $this->config('layout', 'clean-taggable'));

		return (string) $this->firstLayout($docuemnt, $layout)
							 ->display($tag->serializeForDetail($request), array_merge($this->config(), $tag->config)); 
	}   

	/**
	 * Returns the taggable resources.
	 * 
	 * @return array
	 */
	public function taggables()
	{  
		return $this->resourceInformation()->map(function($ignore, $resource) {  
			return $this->resource()->{$resource}()->paginate($this->hasFilter() ? 25 : 3);
		})->filter->isNotEmpty(); 
	}  

    /**
     * Get meta data information about all resources for client side consumption.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $interface
     * @return \Illuminate\Support\Collection
     */
	public function resourceInformation()
	{
		return Helper::resourceInformation(request())->pluck('key')->filter(function($resource) {
			return ! $this->hasFilter() || $this->filteredBy($resource);
		})->flip();
	} 

    /**
     * Determine if the request filtered by the given resource.
     * 
     * @param  string $resource
     * @return boolean 
     */
	public function filteredBy(string $resource)
	{
		return request()->query('taggable') === $resource; 
	} 

    /**
     * Determine if the request filtered by a resource.
     * 
     * @param  string $resource
     * @return boolean 
     */
	public function hasFilter()
	{
		return request()->has('taggable');
	}
}
