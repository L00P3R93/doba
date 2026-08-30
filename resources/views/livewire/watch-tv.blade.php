<div class="baze-wrap" style="padding-top: var(--space-8); padding-bottom: var(--space-12);">
    <a href="/" style="color: var(--color-bone-dim); text-decoration: none; font-size: var(--font-size-sm); display: inline-flex; align-items: center; gap: 6px; margin-bottom: var(--space-6);">
        ← Back to Home
    </a>

    @if (!empty($embedUrl))
        <div class="baze-player-container">
            <iframe src="{{ $embedUrl }}"
                    allowfullscreen
                    allow="autoplay; encrypted-media"></iframe>
        </div>
    @else
        <div class="baze-player-container" style="display: flex; align-items: center; justify-content: center; color: var(--color-bone-dim);">
            <p>Player unavailable for this episode.</p>
        </div>
    @endif

    <div style="margin-top: var(--space-6);">
        <h1 class="baze-display" style="font-size: var(--font-size-2xl); color: var(--color-bone);">
            {{ $show['name'] ?? 'Untitled' }}
        </h1>
        <div style="color: var(--color-bone-dim); font-size: var(--font-size-sm); margin-top: var(--space-2);">
            <span>Season {{ $season }} · Episode {{ $episode }}</span>
        </div>
    </div>
</div>
