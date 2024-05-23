<?php

namespace App\Http\Controllers\UserDetails;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use LSD\Helper\Accessors\MethodResponse;
use Mockery\Generator\Method;

class GetUserDetailsController extends Controller
{
    public function router(Request $request)
    {
        $version = $request->header("version");

        switch ($version) {
            case "v1":
            case "v2":
            case "v3":
            default:
                return parseMethodToJsonResponse($this->getUserDetailsV1($request));
        }
    }

    private function getUserDetailsV1(Request $request): MethodResponse
    {
        $methodResponse = new MethodResponse();
        try {
            $methodResponse->setStatus(true)
                ->setMessage("User details fetched successfully!")
                ->setData([
                    "user" => $request->user()
                ])->setCode("200");
        } catch (Exception $e) {
            $methodResponse->setStatus(false)
                ->setMessage("Failed to fetch user details due to exception: " . $e->getMessage())
                ->setData([
                    "stack-trace" => $e->getTraceAsString()
                ])
                ->setCode("500");
        }

        return $methodResponse;
    }
}
