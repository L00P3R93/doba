<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendEmailVerificationJob implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public $backoff = [10, 30, 60];

    public $deleteWhenMissingModels = true;

    public function __construct(
        private User $user
    ) {}

    public function handle(): void
    {
        if ($this->user && ! $this->user->hasVerifiedEmail()) {
            $this->user->notify(new \App\Notifications\VerifyEmailNotification());
        }
    }

    public function failed(\Throwable $exception): void
    {
        \Log::error('Email verification job failed', [
            'user_id' => $this->user?->id,
            'email' => $this->user?->email,
            'error' => $exception->getMessage(),
        ]);
    }
}
