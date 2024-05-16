<?php

namespace App\Http\Controllers\ScreenConfig;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScreenConfig\CreateScreenConfigsRequest;

class CreateScreenConfigsController extends Controller
{
    public function router(CreateScreenConfigsRequest $request) {
        $version = cleanStr($request->header('version'));

        switch ($version){
            case "v1":
            case "v2":
            case "v3":
            default:
                return parseMethodToJsonResponse($this->getScreenConfigV1($request));
        }
    }
}
