<?php

namespace App\Models\Repositories;

use App\Models\User;
use LSD\Model\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }
}
