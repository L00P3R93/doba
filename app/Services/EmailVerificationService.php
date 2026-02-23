<?php

namespace App\Services;

use App\Jobs\SendEmailVerificationJob;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class EmailVerificationService
{
    public function sendVerificationEmail(User $user): bool
    {
        try {
            if ($user->hasVerifiedEmail()) {
                Log::info('User already has verified email', ['user_id' => $user->id]);

                return false;
            }

            SendEmailVerificationJob::dispatch($user);

            Log::info('Email verification job dispatched', ['user_id' => $user->id, 'email' => $user->email]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to dispatch email verification job', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function resendVerificationEmail(User $user): bool
    {
        try {
            if ($user->hasVerifiedEmail()) {
                return false;
            }

            // Check if verification email was sent recently (within last 60 seconds)
            $recentVerification = $user->notifications()
                ->where('type', 'App\Notifications\VerifyEmailNotification')
                ->where('created_at', '>', now()->subMinute())
                ->exists();

            if ($recentVerification) {
                Log::info('Verification email sent recently, skipping', ['user_id' => $user->id]);

                return false;
            }

            SendEmailVerificationJob::dispatch($user);

            Log::info('Email verification job re-dispatched', ['user_id' => $user->id, 'email' => $user->email]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to re-dispatch email verification job', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function isEmailVerified(User $user): bool
    {
        return $user->hasVerifiedEmail();
    }

    public function canResendVerificationEmail(User $user): bool
    {
        if ($user->hasVerifiedEmail()) {
            return false;
        }

        // Check if verification email was sent recently (within last 60 seconds)
        $recentVerification = $user->notifications()
            ->where('type', 'App\Notifications\VerifyEmailNotification')
            ->where('created_at', '>', now()->subMinute())
            ->exists();

        return ! $recentVerification;
    }
}
