<x-layouts::auth>
    <div class="main-wrapper">

        <!-- LEFT BRAND SECTION -->
        <div class="left-side">
            <h1>DobaPlay</h1>
            <p>Turn your music into income. Build your name. Own your future.</p>

            <div class="feature"><i class="fa-solid fa-key"></i> Reset your password</div>
            <div class="feature"><i class="fa-solid fa-shield-halved"></i> Secure your account</div>
            <div class="feature"><i class="fa-solid fa-user-lock"></i> Regain access</div>
        </div>

        <!-- RIGHT LOGIN -->
        <div class="right-side">
            <div class="login-box">
                <x-auth-header :title="__('Reset password')" :description="__('Please enter your new password below')" />

                <!-- Session Status -->
                <x-auth-session-status class="text-center" :status="session('status')" />

                <form method="POST" action="{{ route('password.update') }}" class="mt-4">
                    @csrf
                    <!-- Token -->
                    <input type="hidden" name="token" value="{{ request()->route('token') }}">

                    <div class="mb-4">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="{{ __('Email Address') }}" value="{{ request('email') }}" required autocomplete="email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 position-relative">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="{{ __('Password') }}" required autocomplete="new-password">
                        <button type="button" class="btn password-toggle" onclick="togglePassword('password')">
                            <i class="fa fa-eye" id="password-eye"></i>
                        </button>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 position-relative">
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="{{ __('Confirm password') }}" required autocomplete="new-password">
                        <button type="button" class="btn password-toggle" onclick="togglePassword('password_confirmation')">
                            <i class="fa fa-eye" id="password_confirmation-eye"></i>
                        </button>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-gold">{{ __('Reset password') }}</button>
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
