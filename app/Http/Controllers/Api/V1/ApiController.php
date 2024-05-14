<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class ApiController extends Controller
{
    use ApiResponses;

    /**
     * The policy class to use for a specified resource. Overridden by the extending controller
     *
     * @var string $policyClass
     */
    protected string $policyClass;

    /**
     * Include a relationship with queried data
     *
     * @param string $relationship
     * @return bool
     */
    public function include(string $relationship): bool
    {
        $param = request()->query('include');

        if(!isset($param)) {
            return false;
        }

        $includeValues = explode(',', strtolower($param));

        return in_array($relationship, $includeValues);
    }

    /**
     * Determine if a user is authorized with the specified ability on the specified resource
     *
     * @param string $ability
     * @param string|Model $targetModel
     * @return bool
     */
    public function isAble(string $ability, string|Model $targetModel): bool
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
