<div class="baze-wrap" style="padding-top: var(--space-8); padding-bottom: var(--space-12);">
    <a href="/" style="color: var(--color-bone-dim); text-decoration: none; font-size: var(--font-size-sm); display: inline-flex; align-items: center; gap: 6px; margin-bottom: var(--space-6);">
        ← Back to Home
    </a>

    <h1 class="baze-display" style="font-size: var(--font-size-2xl); color: var(--color-bone);">
        {{ $show['name'] ?? 'Untitled' }}
    </h1>
    <div style="display: flex; gap: var(--space-4); color: var(--color-bone-dim); font-size: var(--font-size-sm); margin-top: var(--space-2);">
        @if (!empty($show['year']))
            <span>{{ $show['year'] }}</span>
        @endif
        @if (!empty($show['genre']))
            <span>{{ $show['genre'] }}</span>
        @endif
    </div>
    @if (!empty($show['overview']))
        <p style="color: var(--color-bone-dim); margin-top: var(--space-4); max-width: 70ch; line-height: 1.6;">
            {{ $show['overview'] }}
        </p>
    @endif

    <p style="color: var(--color-bone-dim); margin-top: var(--space-6); font-size: var(--font-size-sm);">
        Season and episode selection coming soon.
    </p>
</div>
