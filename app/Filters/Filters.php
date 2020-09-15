<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
    protected $request, $builder;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;

        // // Using collect method
        // // collect array flip it than use
        // // collect filter method to filter
        // // and use collect each method to run it after filter
        // // collect($this->getFilters())->flip()
        //     ->filter(function ($filter) {
        //         return method_exists($this, $filter);
        //     })
        //     ->each(function ($filter, $value) {
        //         $this->$filter($value);
        //     });

        // using foreach loop method
        foreach ($this->getFilters() as $method => $value) {
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
        return $this->builder;
    }

    public function getFilters()
    {
        return $this->request->only($this->filters);
    }
}
