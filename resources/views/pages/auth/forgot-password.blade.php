<x-layouts::auth>
    <div class="main-wrapper">

        <!-- LEFT BRAND SECTION -->
        <div class="left-side">
            <h1>DobaPlay</h1>
            <p>Turn your music into income. Build your name. Own your future.</p>

            <div class="feature"><i class="fa-solid fa-chart-line"></i> Earn from every stream</div>
            <div class="feature"><i class="fa-solid fa-calendar-check"></i> Promote events & shows</div>
            <div class="feature"><i class="fa-solid fa-headphones"></i> Sell beats as a producer</div>
        </div>

        <!-- RIGHT LOGIN -->
        <div class="right-side">
            <div class="login-box">
                <x-auth-header :title="__('Forgot password')" :description="__('Enter your email to receive a password reset link')" />

                <!-- Session Status -->
                <x-auth-session-status class="text-center" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="mt-4">
                    @csrf

                    <div class="mb-4">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="{{ __('Email Address') }}" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-gold">{{ __('Email password reset link') }}</button>

                    <div class="links space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
                        <span>{{ __('Or, return to') }}</span>
                        <flux:link :href="route('login')" wire:navigate>{{ __('log in') }}</flux:link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts::auth>
