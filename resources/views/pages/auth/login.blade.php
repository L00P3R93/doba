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
                <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />
                <!-- Session Status -->
                <x-auth-session-status class="text-center" :status="session('status')" />

                <form method="POST" action="{{ route('login.store') }}" class="mt-4">
                    @csrf

                    <div class="mb-4">
                        <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                    </div>

                    <div class="mb-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    </div>

                    <div class="relative mb-3">
                        <!-- Remember Me -->
                        <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

                        @if (Route::has('password.request'))
                            <div class="links">
                                <flux:link class="absolute text-sm end-0 top-0" :href="route('password.request')" wire:navigate>
                                    {{ __('Forgot your password?') }}
                                </flux:link>
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn-gold">Login</button>

                    @if (Route::has('register'))
                        <div class="links space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
                            <span>{{ __('Don\'t have an account?') }}</span>
                            <flux:link :href="route('register')" wire:navigate>{{ __('Sign up') }}</flux:link>
                        </div>
                    @endif
                </form>
            </div>
        </div>

    </div>
</x-layouts::auth>
