<?php

namespace App\Actions\Auth\Login;

use App\Models\Repositories\UserRepository;
use LSD\Model\Builder\RepositoryBuilder;

class GenerateAppTokenAction
{
    private RepositoryBuilder $repositoryBuilder;

    public function __construct(RepositoryBuilder $repositoryBuilder) {
        $this->repositoryBuilder = $repositoryBuilder;
    }

    public function execute (string $email) {
        $user = $this->repositoryBuilder->setRepo(UserRepository::class)
            ->addWhere([
                ["email", $email]
            ])
            ->fetch()
            ->selectOne();
        return $user->createToken('AppToken')->plainTextToken;
    }
}
