<?php

namespace App\Models\Repositories;

use App\Models\ScreenConfig;
use LSD\Model\Repositories\BaseRepository;

class ScreenConfigRepository extends BaseRepository
{
    public function __construct(ScreenConfig $screenConfig)
    {
        parent::__construct($screenConfig);
    }
}
