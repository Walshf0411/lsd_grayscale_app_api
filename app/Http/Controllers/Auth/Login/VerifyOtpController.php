<?php

namespace App\Http\Controllers\Auth\Login;

use App\Actions\Auth\Login\GenerateAppTokenAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Login\VerifyOtpRequest;
use App\Models\Repositories\OtpRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use LSD\Helper\Accessors\MethodResponse;
use LSD\Model\Builder\RepositoryBuilder;

class VerifyOtpController extends Controller
{
    private RepositoryBuilder $repositoryBuilder;
    private GenerateAppTokenAction $generateAppTokenAction;
    public function __construct(RepositoryBuilder $repositoryBuilder, GenerateAppTokenAction $generateAppTokenAction)
    {
        $this->repositoryBuilder = $repositoryBuilder;
        $this->generateAppTokenAction = $generateAppTokenAction;
    }


    public function router(VerifyOtpRequest $request) {
        $version = cleanStr($request->header('version'));

        switch ($version){
            case "v1":
            case "v2":
            default:
                return parseMethodToJsonResponse($this->verifyOtpV1($request));
        }
    }

    public function verifyOtpV1(VerifyOtpRequest $request) {
        $methodResponse = new MethodResponse();

        try {
            $currentTime = Carbon::now();
            $persistedOtp = $this->repositoryBuilder->setRepo(OtpRepository::class)
                ->addWhere([
                    ["identifier", "=", $request->identifier],
                    ["expires_at", ">", $currentTime]
                ])->fetch()->selectOne();

            if(!$persistedOtp || !Hash::check($request->otp, $persistedOtp->otp)) {
                $methodResponse->setStatus(false)
                    ->setMessage("Invalid OTP")
                    ->setData([])
                    ->setCode("400");
            } else {
                $methodResponse->setStatus(true)
                    ->setMessage("OTP verified successfully")
                    ->setData([
                        "token" => $this->generateAppTokenAction->execute($request->identifier)
                    ])->setCode("200");
            }
        } catch (Exception $e) {
            $methodResponse->setStatus(false)
                ->setMessage("Failed to verify OTP due to exception: " . $e->getMessage())
                ->setData([
                    "stack-trace"=> $e->getTraceAsString()
                ])->setCode("500");
        }

        return $methodResponse;
    }
}
