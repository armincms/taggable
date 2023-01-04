<?php

namespace Armincms\Taggable\Cypress\Fragments;

use Armincms\Contract\Concerns\InteractsWithModel;
use Zareismail\Cypress\Contracts\Resolvable;
use Zareismail\Cypress\Fragment;

class Tag extends Fragment implements Resolvable
{
    use InteractsWithModel;

    /**
     * Get the resource Model class.
     *
     * @return
     */
    public function model(): string
    {
        return \Armincms\Taggable\Models\Tag::class;
    }

    /**
     * Apply custom query to the given query.
     *
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applyQuery($request, $query)
    {
        return $query->unless(\Auth::guard('admin')->check(), fn ($query) => $query->published());
    }
}
