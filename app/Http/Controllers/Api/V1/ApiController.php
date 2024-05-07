<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ApiController extends Controller
{
    use ApiResponses;
    public function include(string $relationship): bool
    {
        $param = request()->query('include');

        if(!isset($param)) {
            return false;
        }

        $includeValues = explode(',', strtolower($param));

        return in_array($relationship, $includeValues);
    }
}
