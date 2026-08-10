<nav class="baze-nav">
    <div class="baze-wrap baze-nav-inner">
        <a href="{{ route('home') }}" class="baze-display baze-nav-logo">Doba<span class="baze-accent">Play</span></a>

        <div class="baze-nav-links">
            <a href="{{ url('/#plans') }}">Creators</a>
            <a href="{{ route('pricing') }}">Listeners</a>
            <a href="{{ url('/#cinema') }}">Cinema</a>
            <a href="{{ route('advertise') }}">Advertise With Us</a>
        </div>

        <div class="baze-nav-cta">
            @auth
                <a href="{{ route('dashboard') }}" class="baze-btn baze-btn-primary">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="baze-btn baze-btn-ghost">Log in</a>
                <a href="{{ route('register') }}" class="baze-btn baze-btn-primary">Create account</a>
            @endauth
        </div>
    </div>
</nav>
