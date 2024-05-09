<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class ApiController extends Controller
{
    use ApiResponses;

    protected string $policyClass;
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
        $gate = Gate::policy(is_string($targetModel) ? $targetModel : $targetModel::class, $this->policyClass);
        try {
            $gate->authorize($ability, [$targetModel]);
            return true;
        } catch (AuthorizationException $e) {
            return false;
        }
    }
}
