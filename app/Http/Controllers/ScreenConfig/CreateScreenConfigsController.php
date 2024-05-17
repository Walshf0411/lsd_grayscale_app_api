<?php

namespace App\Http\Controllers\ScreenConfig;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScreenConfig\CreateScreenConfigsRequest;
use Exception;
use LSD\Model\Builder\RepositoryBuilder;
use LSD\Helper\Accessors\MethodResponse;
use App\Models\Repositories\ScreenConfigRepository;
use App\Models\Repositories\ScreenSectionRepository;

class CreateScreenConfigsController extends Controller
{

    private RepositoryBuilder $repositoryBuilder;

    public function __construct(RepositoryBuilder $repositoryBuilder) {
        $this->repositoryBuilder = $repositoryBuilder;
    }

    public function router(CreateScreenConfigsRequest $request) {
        $version = cleanStr($request->header('version'));

        switch ($version){
            case "v1":
            case "v2":
            case "v3":
            default:
                return parseMethodToJsonResponse($this->createScreenConfigsV1($request));
        }
    }

    public function createScreenConfigsV1(CreateScreenConfigsRequest $request) {
        $methodResponse = new MethodResponse();
        try {
            $screenConfig = $this->repositoryBuilder->setRepo(ScreenConfigRepository::class)
                ->fetch()
                ->create([
                    "screen_name" => $request->screen_name
                ]);
            $sections = $request->sections;

            for ($i = 0; $i < count($sections); $i++) {
                $sections[$i]['screen_config_id'] = $screenConfig->id;
            }

            $screenConfig->sections()->insert($sections);

            $methodResponse->setStatus(true)
                ->setMessage("ScreenConfigs Created successfully!")
                ->setData([
                    "screen_config" => $screenConfig->with("sections")
                ])
                ->setCode("200");
        } catch(Exception $e) {
            $methodResponse->setStatus(false)
                ->setMessage("Failed to create screen configs due to exception: " . $e->getMessage())
                ->setData([
                    "stack-trace" => $e->getTraceAsString()
                ])
                ->setCode("500");
        }

        return $methodResponse;
    }
}
