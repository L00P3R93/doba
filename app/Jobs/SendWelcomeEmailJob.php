<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\WelcomeEmailNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendWelcomeEmailJob implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public $backoff = [10, 30, 60];

    public $deleteWhenMissingModels = true;

    public function __construct(
        private User $user,
        private string $password
    ) {}

    public function handle(): void
    {
        if ($this->user) {
            $this->user->notify(new WelcomeEmailNotification($this->password));
        }
    }

    public function failed(\Throwable $exception): void
    {
        \Log::error('Welcome email job failed', [
            'user_id' => $this->user?->id,
            'email' => $this->user?->email,
            'error' => $exception->getMessage(),
        ]);
    }
}
