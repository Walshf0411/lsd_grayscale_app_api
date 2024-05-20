<?php

namespace App\Http\Controllers\Auth\Login;

use App\Actions\Auth\Login\GenerateOtpAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Login\GenerateOtpRequest;
use App\Models\Repositories\OtpRepository;
use App\Models\Repositories\UserRepository;
use App\Notifications\OtpNotification;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use LSD\Helper\Accessors\MethodResponse;
use LSD\Model\Builder\RepositoryBuilder;

class OtpLoginController extends Controller
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
            $user = $this->repositoryBuilder->setRepo(UserRepository::class)
                ->addWhere([
                    ["email", "=", $generateOtpRequest->identifier]
                ])->fetch()->selectOne();

            if(!$user) {
                Log::warning("Attempted to generate OTP for non-existent user: " . $generateOtpRequest->identifier);

                $methodResponse->setStatus(true)
                    ->setMessage("Non-existent user!")
                    ->setData([])
                    ->setCode("400");
            } else {
                $otpDetails = $this->generateOtpAction->execute($generateOtpRequest);

                Notification::route('mail', $generateOtpRequest->identifier)
                    ->notify(new OtpNotification($otpDetails));

                $methodResponse->setStatus(true)
                    ->setMessage("OTP generated successfully")
                    ->setData($otpDetails)->setCode("200");
            }
        } catch(Exception $e) {
            $methodResponse->setStatus(true)
                ->setMessage("Failed to generate OTP due to exception: " . $e->getMessage())
                ->setData([
                    "stack-trace" => $e->getTraceAsString()
                ])
                ->setCode("500");
        }

        return $methodResponse;
    }
}
