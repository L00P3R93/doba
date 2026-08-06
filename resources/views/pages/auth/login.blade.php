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
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email Address" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 position-relative">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password" required>
                        <button type="button" class="btn password-toggle" onclick="togglePassword('password')">
                            <i class="fa fa-eye" id="password-eye"></i>
                        </button>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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

                    <!-- Divider -->
                    <div class="flex items-center gap-3">
                        <div class="h-px flex-1 bg-zinc-700"></div>
                        <span class="text-xs text-zinc-500">{{ __('or') }}</span>
                        <div class="h-px flex-1 bg-zinc-700"></div>
                    </div>

                    <!-- Google OAuth -->
                    <a href="{{ route('auth.google') }}"
                       class="flex w-full items-center justify-center gap-3 rounded-lg border border-zinc-700 bg-zinc-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-zinc-800">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="h-5 w-5" aria-hidden="true">
                            <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                            <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                            <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                            <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                            <path fill="none" d="M0 0h48v48H0z"/>
                        </svg>
                        {{ __('Continue with Google') }}
                    </a>

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
    <script>
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(fieldId + '-eye');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</x-layouts::auth>
