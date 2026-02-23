<?php

namespace App\Actions\Fortify;

use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class VerifyEmail
{
    public function __invoke(MustVerifyEmail $user): bool
    {
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            
            event(new Verified($user));
            
            return true;
        }

        return false;
    }
}
