<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(title="Posts Management API", version="1.0")
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getFilterableFields(Request $request, array $filterOptions): array
    {
        $filterableOptions = [];

        foreach ($filterOptions as $filterOption) {
            $requestField = $request->input($filterOption);
            if ($requestField !== null) {
                $filterableOptions[$filterOption] = $requestField;
            }
        }

        return $filterableOptions;
    }
}
