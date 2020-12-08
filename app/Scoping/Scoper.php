<?php

namespace App\Scoping;

use Illuminate\Http\Request;
use App\Scoping\Contracts\Scope;
use Illuminate\Database\Eloquent\Builder;

class Scoper
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder, array $scopes)
    {
        foreach ($scopes as $key => $scope) {
            if (!$scope instanceof Scope) {
                continue;
            }

            if ($this->request->get($key) == null) {
                break;
            }

            $scope->apply($builder, $this->request->get($key));
        }

        return $builder;
    }
}
