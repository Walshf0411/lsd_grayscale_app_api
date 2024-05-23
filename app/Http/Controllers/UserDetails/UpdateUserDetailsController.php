<?php

namespace App\Http\Controllers\UserDetails;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserDetails\UpdateUserDetailsRequest;
use App\Models\Repositories\UserRepository;
use Exception;
use LSD\Helper\Accessors\MethodResponse;
use LSD\Model\Builder\RepositoryBuilder;

class UpdateUserDetailsController extends Controller
{
    private RepositoryBuilder $repositoryBuilder;

    public function __construct(RepositoryBuilder $repositoryBuilder)
    {
        $this->repositoryBuilder = $repositoryBuilder;
    }

    public function router(UpdateUserDetailsRequest $request) {
        $version = $request->header("version");

        switch ($version) {
            case "v1":
            case "v2":
            case "v3":
            default:
                return parseMethodToJsonResponse($this->updateUserDetailsV1($request));
        }
    }

    private function updateUserDetailsV1(UpdateUserDetailsRequest $request): MethodResponse {
        $methodResponse = new MethodResponse();

        try {
            $user = $request->user();
//            dd($this->getFieldsToUpdate($request));
            $this->repositoryBuilder->setRepo(UserRepository::class)
                ->fetch()
                ->select($user->id)
                ->update($this->getFieldsToUpdate($request));

            $user->refresh();
            $methodResponse->setStatus(true)
                ->setMessage("User details updated successfully!")
                ->setData([
                    "updatedUser" => $user
                ])
                ->setCode("200");
        } catch (Exception $e) {
            $methodResponse->setStatus(false)
                ->setMessage("Failed to update user details due to exception: " . $e->getMessage())
                ->setData([
                    "stack-trace" => $e->getTraceAsString()
                ])->setCode("500");
        }

        return $methodResponse;
    }

    private function getFieldsToUpdate(UpdateUserDetailsRequest $request) {
        $fieldsToUpdate = [];

        if($request->first_name) {
            $fieldsToUpdate["first_name"] = $request->first_name;
        }

        if($request->last_name) {
            $fieldsToUpdate["last_name"] = $request->last_name;
        }

        if($request->date_of_birth) {
            $fieldsToUpdate["date_of_birth"] = $request->date_of_birth;
        }

        if($request->mobile_number) {
            $fieldsToUpdate["mobile_number"] = $request->mobile_number;
        }

        if($request->gender) {
            $fieldsToUpdate["gender"] = $request->gender;
        }

        return $fieldsToUpdate;
    }
}
