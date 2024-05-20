<?php

namespace App\JsonApi;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class JsonApiQuery
{

    public function allowedFilters(): Closure
    {

        return function($allowedFilters){
            /** @var Builder $this */
            foreach(request('filter',[]) as $filter => $value){

                abort_unless(in_array($filter,$allowedFilters),400);

                if($this->hasNamedScope($filter)){

                    $this->{$filter}($value);

                } else {

                    $this->where($filter,'=', $value );

                }

            }

            return $this;

        };

    }

}
