<?php

namespace App\Http\Controllers\Auth\Login;

use App\Actions\Auth\Login\GenerateOtpAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Login\GenerateOtpRequest;
use App\Models\Repositories\OtpRepository;
use Exception;
use LSD\Helper\Accessors\MethodResponse;
use LSD\Model\Builder\RepositoryBuilder;

class GenerateOtpController extends Controller
{
    private RepositoryBuilder $repositoryBuilder;
    private GenerateOtpAction $generateOtpAction;

    public function __construct(RepositoryBuilder $repositoryBuilder, GenerateOtpAction $generateOtpAction) {
        $this->repositoryBuilder = $repositoryBuilder;
        $this->generateOtpAction = $generateOtpAction;
    }

    public function router(GenerateOtpRequest $generateOtpRequest) {
        $version = cleanStr($generateOtpRequest->header('version'));
        switch ($version){
            case "v1":
            case "v2":
            case "v3":
            default:
                return parseMethodToJsonResponse($this->generateOtpV1($generateOtpRequest));
        }
    }

    private function generateOtpV1(GenerateOtpRequest $generateOtpRequest) {
        $methodResponse = new MethodResponse();

        try {
            $otp = $this->generateOtpAction->execute($generateOtpRequest);
        } catch(Exception $e) {

        }

        return $methodResponse;
    }
}
