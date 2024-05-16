<?php

namespace App\Http\Controllers\Auth\Signup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Signup\EmailPasswordSignupRequest;
use App\Models\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use LSD\Helper\Accessors\MethodResponse;
use LSD\Model\Builder\RepositoryBuilder;
use Exception;

class EmailPasswordSignupController extends Controller
{
    private RepositoryBuilder $repositoryBuilder;
    public function __construct(RepositoryBuilder $repositoryBuilder) {
        $this->repositoryBuilder = $repositoryBuilder;
    }

    public function router(EmailPasswordSignupRequest $request) {
        $version = cleanStr($request->header('version'));
        switch ($version){
            case "v1":
            case "v2":
            case "v3":
            default:
                return parseMethodToJsonResponse($this->signupEmailPassword($request));
        }
    }

    private function signupEmailPassword(EmailPasswordSignupRequest $request): MethodResponse {
        $methodResponse = new MethodResponse();
        try {
            $user = $this->repositoryBuilder->setRepo(UserRepository::class)
                ->fetch()
                ->create([
                    "name" => $request->name,
                    "email" => $request->email,
                    "password" => Hash::make($request->password),
                ]);
            $methodResponse->setStatus(true)
                ->setMessage("User signed up successfully")
                ->setData([
                    "user" => $user
                ])
                ->setCode("200");
        }catch (Exception $e) {
            $methodResponse->setStatus(false)
                ->setMessage("Failed to signup up user: " . $e->getMessage())
                ->setData([
                    "stack_trace" => $e->getTraceAsString()
                ])
                ->setCode("500");
        }

        return $methodResponse;
    }
}
