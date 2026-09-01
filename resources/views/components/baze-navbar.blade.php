<nav
    class="baze-nav"
    x-data="{
        mobileOpen: false,
        init() {
            window.addEventListener('resize', () => {
                if (window.innerWidth > 900) this.mobileOpen = false;
            });
        }
    }"
    x-init="init()"
    @keydown.escape.window="mobileOpen = false"
>
    <div class="baze-wrap baze-nav-inner">
        <a href="{{ route('home') }}" class="baze-display baze-nav-logo">Doba<span class="baze-accent">Play</span></a>

        <div class="baze-nav-links">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('pricing') }}">Pricing</a>
            <a href="{{ route('advertise') }}">Advertise With Us</a>
        </div>

        <div class="baze-nav-cta">
            <livewire:search />
            @auth
                <a href="{{ route('dashboard') }}" class="baze-btn baze-btn-primary">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="baze-btn baze-btn-ghost">Log in</a>
                <a href="{{ route('register') }}" class="baze-btn baze-btn-primary">Create account</a>
            @endauth
        </div>

        <button
            type="button"
            class="baze-nav-toggle"
            @click="mobileOpen = !mobileOpen"
            :aria-expanded="mobileOpen"
            aria-controls="baze-mobile-menu"
            aria-label="Toggle navigation menu"
        >
            <span class="baze-nav-toggle-bar" :class="{ 'is-open': mobileOpen }"></span>
            <span class="baze-nav-toggle-bar" :class="{ 'is-open': mobileOpen }"></span>
            <span class="baze-nav-toggle-bar" :class="{ 'is-open': mobileOpen }"></span>
        </button>
    </div>

    {{-- Mobile panel --}}
    <div
        id="baze-mobile-menu"
        class="baze-nav-mobile"
        x-show="mobileOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
    >
        <div class="baze-wrap baze-nav-mobile-inner">
            <a href="{{ route('home') }}" @click="mobileOpen = false">Home</a>
            <a href="{{ route('pricing') }}" @click="mobileOpen = false">Pricing</a>
            <a href="{{ route('advertise') }}" @click="mobileOpen = false">Advertise With Us</a>

            <div class="baze-nav-mobile-cta">
                @auth
                    <a href="{{ route('dashboard') }}" class="baze-btn baze-btn-primary" @click="mobileOpen = false">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="baze-btn baze-btn-ghost" @click="mobileOpen = false">Log in</a>
                    <a href="{{ route('register') }}" class="baze-btn baze-btn-primary" @click="mobileOpen = false">Create account</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
