<?php

namespace App\Models\Repositories;

use App\Models\Otp;
use LSD\Model\Repositories\BaseRepository;

class OtpRepository extends BaseRepository{

    public function __construct(Otp $otp) {
        parent::__construct($otp);
    }
}
