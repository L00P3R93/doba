<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use App\Services\EmailVerificationService;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Str;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    private EmailVerificationService $emailVerificationService;

    public function __construct(EmailVerificationService $emailVerificationService)
    {
        $this->emailVerificationService = $emailVerificationService;
    }

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
        ])->validate();

        $user = User::query()->create([
            'name' => $input['name'],
            'username' => Str::slug($input['name']),
            'email' => $input['email'],
            'phone' => $input['phone'] ?? null,
            'password' => $input['password'],
            'account_no' => $this->generateUniqueAccountNumber(),
        ]);

        $user->assignRole('Guest');

        // Send email verification
        $this->emailVerificationService->sendVerificationEmail($user);

        return $user;
    }

    private function generateUniqueAccountNumber(): string
    {
        do {
            $accountNo = 'DOB'.str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (User::where('account_no', $accountNo)->exists());

        return $accountNo;
    }
}
