<?php

namespace App\Models\Repositories;

use App\Models\ScreenSection;
use LSD\Model\Repositories\BaseRepository;

class ScreenSectionRepository extends BaseRepository
{
    public function __construct(ScreenSection $screenSection)
    {
        parent::__construct($screenSection);
    }
}
