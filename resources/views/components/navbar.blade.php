<a href="#main-content" class="skip-to-content">Skip to main content</a>
<nav class="navbar navbar-expand-lg fixed-top" role="navigation" aria-label="Main navigation">
    <div class="container">
        <a class="navbar-brand fw-bold text-white" href="{{ route('home') }}" aria-label="DobaPlay Home">
            Doba<span>Play</span>.
        </a>

        <button
            class="navbar-toggler text-white focus-ring"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navMenu"
            aria-expanded="false"
            aria-controls="navMenu"
            aria-label="Toggle navigation menu"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
                @if (Route::has('login'))
                    @auth
                        <li class="nav-item">
                            <a class="nav-link focus-ring" href="{{ url('/dashboard') }}" aria-label="{{ auth()->user()->hasRole('Guest') ? 'Go to Subscribe page' : 'Go to Dashboard' }}">
                                {{ auth()->user()->hasRole('Guest') ? __('Subscribe') : __('Dashboard') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="nav-link focus-ring" aria-label="Log out">Log Out</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link focus-ring" href="{{ route('login') }}" aria-label="Go to Login page">Login</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link focus-ring" href="{{ route('register') }}" aria-label="Go to Registration page">Register</a>
                            </li>
                        @endif
                    @endauth
                @endif
                <li class="nav-item">
                    <a class="nav-link focus-ring" href="{{ route('premium') }}" aria-label="Go to Premium page">Premium</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link focus-ring" href="{{ route('advertise') }}" aria-label="Go to Advertising page">Ads</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link focus-ring" href="#" aria-label="Contact us">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
