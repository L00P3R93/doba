<x-layouts::auth>
    <div class="main-wrapper">

        <!-- LEFT BRAND SECTION -->
        <div class="left-side">
            <h1>DobaPlay</h1>
            <p>Turn your music into income. Build your name. Own your future.</p>

            <div class="feature"><i class="fa-solid fa-envelope"></i> Verify your email address</div>
            <div class="feature"><i class="fa-solid fa-shield-halved"></i> Secure your account</div>
            <div class="feature"><i class="fa-solid fa-check-circle"></i> Access all features</div>
        </div>

        <!-- RIGHT VERIFICATION -->
        <div class="right-side">
            <div class="login-box">
                <x-auth-header :title="__('Verify Your Email')" :description="__('Please check your inbox for the verification link')" />
                
                <!-- Session Status -->
                <x-auth-session-status class="text-center" :status="session('status')" />

                @if (session('message'))
                    <div class="alert alert-info mb-4">
                        {{ session('message') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success mb-4">
                        <i class="fa-solid fa-check-circle me-2"></i>
                        {{ __('A new verification link has been sent to your email address.') }}
                    </div>
                @endif

                <div class="mb-4">
                    <flux:text class="text-center">
                        {{ __('Please verify your email address by clicking on the link we just emailed to you.') }}
                    </flux:text>
                </div>

                <div class="d-grid gap-3">
                    <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
                        @csrf
                        <flux:button type="submit" variant="primary" class="w-full">
                            <i class="fa-solid fa-paper-plane me-2"></i>
                            {{ __('Resend verification email') }}
                        </flux:button>
                    </form>

                    <div class="text-center">
                        <small class="text-muted">
                            {{ __('Didn\'t receive the email? Check your spam folder.') }}
                        </small>
                    </div>

                    <hr>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:button variant="ghost" type="submit" class="w-full">
                            <i class="fa-solid fa-sign-out-alt me-2"></i>
                            {{ __('Log out') }}
                        </flux:button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts::auth>
