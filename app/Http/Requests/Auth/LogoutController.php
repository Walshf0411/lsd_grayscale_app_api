<?php

namespace App\Http\Requests\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Exception;
use LSD\Helper\Accessors\MethodResponse;

class LogoutController extends Controller
{

    public function router() {
        return parseMethodToJsonResponse($this->logout());
    }

    private function logout() {
        $methodResponse = new MethodResponse();
        try {
            Auth::user()->tokens()->delete();

            $methodResponse->setStatus(true)
                ->setMessage("User logged out successfully!")
                ->setData([])
                ->setCode("200");
        } catch (Exception $e) {
            $methodResponse->setStatus(false)
                ->setMessage("Exception occurred: " . $e->getMessage())
                ->setData([
                    "stack-trace" => $e->getTraceAsString()
                ])
                ->setCode("500");
        }
        return $methodResponse;
    }
}
