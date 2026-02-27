<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\EmailVerificationService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class EmailVerificationController extends Controller
{
    private EmailVerificationService $emailVerificationService;

    public function __construct(EmailVerificationService $emailVerificationService)
    {
        $this->emailVerificationService = $emailVerificationService;
    }
    public function notice(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return Redirect::intended(config('fortify.home', '/dashboard'));
        }

        return view('pages::auth.verify-email')->with([
            'message' => session('message'),
        ]);
    }

    public function verify(Request $request, $id, $hash)
    {
        $user = \App\Models\User::findOrFail($id);

        // Check if the URL has a valid signature
        if (! $request->hasValidSignature()) {
            return Redirect::route('verification.notice')
                ->with('error', 'Invalid or expired verification link.');
        }

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return Redirect::route('verification.notice')
                ->with('error', 'Invalid verification link.');
        }

        if ($user->hasVerifiedEmail()) {
            return Redirect::intended(config('fortify.home', '/dashboard'))
                ->with('success', 'Your email has already been verified.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return Redirect::intended(config('fortify.home', '/dashboard'))
            ->with('success', 'Thank you for verifying your email address!');
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return Redirect::intended(config('fortify.home'))
                ->with('info', 'Your email is already verified.');
        }

        $this->emailVerificationService->resendVerificationEmail($request->user());

        return Redirect::back()
            ->with('status', 'verification-link-sent');
    }
}
