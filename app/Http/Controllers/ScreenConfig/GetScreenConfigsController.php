<?php declare(strict_types = 1);

namespace App\Http\Controllers\ScreenConfig;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScreenConfig\GetScreenConfigsRequest;
use App\Models\Repositories\ScreenConfigRepository;
use LSD\Helper\Accessors\MethodResponse;
use LSD\Model\Builder\RepositoryBuilder;
use Exception;

class GetScreenConfigsController extends Controller
{
    private RepositoryBuilder $repositoryBuilder;
    public function __construct(RepositoryBuilder $repositoryBuilder)
    {
        $this->repositoryBuilder = $repositoryBuilder;
    }

    public function router(GetScreenConfigsRequest $request) {
        $version = cleanStr($request->header('version'));

        switch ($version){
            case "v1":
            case "v2":
            case "v3":
            default:
                return parseMethodToJsonResponse($this->getScreenConfigV1($request));
        }
    }

    private function getScreenConfigV1(GetScreenConfigsRequest $request): MethodResponse {
        $methodResponse = new MethodResponse();
        try {
            $screenConfigs = $this->repositoryBuilder->setRepo(ScreenConfigRepository::class)
                ->addWhere([
                    ["screen_name", $request->screen_name]
                ])
                ->addSelect([
                    "with" => ["sections"]
                ])->fetch()->selectOne();

            $methodResponse->setStatus(true)
                ->setMessage("Screen Configs fetched successfully!")
                ->setData($screenConfigs)
                ->setCode("200");
        } catch(Exception $e) {
            $methodResponse->setStatus(false)
                ->setMessage("Exception occurred in fetching screen configs: " . $e->getMessage())
                ->setData([
                    "stack-trace" => $e->getTraceAsString()
                ])
                ->setCode("500");
        }

        return $methodResponse;
    }
}
