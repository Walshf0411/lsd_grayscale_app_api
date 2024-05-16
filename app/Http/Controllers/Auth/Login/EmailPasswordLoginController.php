<?php

namespace App\Http\Controllers\Auth\Login;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Login\EmailPasswordLoginRequest;
use App\Models\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use LSD\Helper\Accessors\MethodResponse;
use LSD\Model\Builder\RepositoryBuilder;

class EmailPasswordLoginController extends Controller
{
    private RepositoryBuilder $repositoryBuilder;
    public function __construct(RepositoryBuilder $repositoryBuilder)
    {
        $this->repositoryBuilder = $repositoryBuilder;
    }

    public function router(EmailPasswordLoginRequest $request) {
        $version = cleanStr($request->header('version'));

        switch ($version){
            case "v1":
            case "v2":
            case "v3":
            default:
                return parseMethodToJsonResponse($this->emailPasswordLoginV1($request));
        }
    }

    private function emailPasswordLoginV1(EmailPasswordLoginRequest $request): MethodResponse {
        $methodResponse = new MethodResponse();

        try {
            if(Auth::attempt(["email" => $request->email, "password" => $request->password])) {
                $user = $this->repositoryBuilder->setRepo(UserRepository::class)
                    ->addWhere([
                        ["email", $request->email]
                    ])
                    ->fetch()
                    ->selectOne();
                $token = $user->createToken('AppToken')->plainTextToken;

                $methodResponse->setStatus(true)
                    ->setMessage("User logged in successfully!")
                    ->setData([
                        "token" => $token
                    ])->setCode("200");
            }
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
