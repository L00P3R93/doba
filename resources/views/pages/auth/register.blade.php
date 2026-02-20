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
                        <input type="text" class="form-control" name="name" placeholder="{{ __('Full name') }}" value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-3">
                        <input type="email" class="form-control" name="email" placeholder="{{ __('Email Address') }}"  value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    </div>

                    <div class="mb-3">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
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
</x-layouts::auth>
