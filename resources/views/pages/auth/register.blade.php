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
                <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

                <!-- Session Status -->
                <x-auth-session-status class="text-center" :status="session('status')" />

                <form method="POST" action="{{ route('register.store') }}" class="mt-4">
                    @csrf

                    <div class="mb-4">
                        <!-- Name -->
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="{{ __('Full name') }}" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="{{ __('Email Address') }}" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" placeholder="{{ __('Phone Number (e.g., 254712345678 or 0712345678)') }}" value="{{ old('phone') }}">
                        @error('phone')
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

                    <div class="mb-3 position-relative">
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                        <button type="button" class="btn password-toggle" onclick="togglePassword('password_confirmation')">
                            <i class="fa fa-eye" id="password_confirmation-eye"></i>
                        </button>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-gold">Create Account</button>

                    <div class="links space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
                        <span>{{ __('Already have an account?') }}</span>
                        <flux:link :href="route('login')" wire:navigate>{{ __('Sign in') }}</flux:link>
                    </div>
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
