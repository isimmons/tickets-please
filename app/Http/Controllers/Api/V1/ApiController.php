<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ApiController extends Controller
{
    use ApiResponses;

    protected $policyClass;
    public function include(string $relationship): bool
    {
        $param = request()->query('include');

        if(!isset($param)) {
            return false;
        }

        $includeValues = explode(',', strtolower($param));

        return in_array($relationship, $includeValues);
    }

    public function isAble($ability, $targetModel)
    {
        $gate = Gate::policy($targetModel::class, $this->policyClass);
        return $gate->authorize($ability, [$targetModel]);
    }
}
