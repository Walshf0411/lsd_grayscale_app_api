<?php

namespace App\Actions\Auth\Login;

use App\Http\Requests\Auth\Login\GenerateOtpRequest;
use App\Models\Otp;
use App\Models\Repositories\OtpRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use LSD\Model\Builder\RepositoryBuilder;

class GenerateOtpAction {
    private RepositoryBuilder $repositoryBuilder;

    public function __construct(RepositoryBuilder $repositoryBuilder) {
        $this->repositoryBuilder = $repositoryBuilder;
    }

    public function execute(GenerateOtpRequest $generateOtpRequest) {
        Otp::where('identifier', $generateOtpRequest->identifier)->delete();

        $otpCode = rand(100000, 999999); // Generate a 6-digit numeric OTP
        $expiresAt = Carbon::now()->addMinutes(5);

        $this->repositoryBuilder->setRepo(OtpRepository::class)
            ->fetch()
            ->create([
                'identifier' => $generateOtpRequest->identifier,
                'otp' => Hash::make($otpCode),
                'expires_at' => $expiresAt
            ]);

        return [
            "otp" => $otpCode,
            "expires_at" => $expiresAt
        ];
    }
}
