<x-layouts::auth>
    <div class="main-wrapper">

        <!-- LEFT BRAND SECTION -->
        <div class="left-side">
            <h1>DobaPlay</h1>
            <p>Turn your music into income. Build your name. Own your future.</p>

            <div class="feature"><i class="fa-solid fa-shield-halved"></i> Secure authentication</div>
            <div class="feature"><i class="fa-solid fa-lock"></i> Protect your account</div>
            <div class="feature"><i class="fa-solid fa-user-check"></i> Verify your identity</div>
        </div>

        <!-- RIGHT LOGIN -->
        <div class="right-side">
            <div class="login-box">
                <x-auth-header
                    :title="__('Confirm password')"
                    :description="__('This is a secure area of the application. Please confirm your password before continuing.')"
                />

                <!-- Session Status -->
                <x-auth-session-status class="text-center" :status="session('status')" />

                <form method="POST" action="{{ route('password.confirm.store') }}" class="mt-4">
                    @csrf

                    <div class="mb-4 position-relative">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="{{ __('Password') }}" required autocomplete="current-password">
                        <button type="button" class="btn password-toggle" onclick="togglePassword('password')">
                            <i class="fa fa-eye" id="password-eye"></i>
                        </button>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-gold">{{ __('Confirm') }}</button>
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
