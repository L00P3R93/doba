<div class="baze-watch-page"
     x-data="{
         init() {
             this.$el._startTime = Date.now();
             const timer = setInterval(() => {
                 const iframe = document.querySelector('.baze-player-container iframe');
                 if (iframe) {
                     const elapsed = (Date.now() - this.$el._startTime) / 1000;
                     const progress = Math.min(95, Math.round((elapsed / 7200) * 100));
                     saveWatchProgress({
                         tmdbId: {{ $tmdbId }},
                         type: 'movie',
                         title: '{{ addslashes($movie['title'] ?? '') }}',
                         image: '{{ addslashes($movie['image'] ?? '') }}',
                         progress: progress,
                     });
                 }
             }, 30000);
             this.$once('destroy', () => clearInterval(timer));
         }
     }">
    {{-- Back Navigation --}}
    <nav class="baze-watch-nav">
        <a href="{{ route('home') }}" class="baze-watch-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Back to Home
        </a>
    </nav>

    {{-- Player --}}
    @if (!empty($embedUrl))
        <div class="baze-player-container">
            <iframe
                src="{{ $embedUrl }}"
                title="{{ $movie['title'] ?? 'Movie Player' }}"
                allowfullscreen
                allow="autoplay; encrypted-media"
                loading="lazy"
            ></iframe>
        </div>
    @else
        <div class="baze-player-container baze-player-empty">
            <div class="baze-player-empty-inner">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-bone-muted);"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8" fill="currentColor" stroke="none"/></svg>
                <p>Player unavailable for this title.</p>
            </div>
        </div>
    @endif

    {{-- Controls Bar: Servers --}}
    @if (count($providers) > 1)
        <div class="baze-watch-controls">
            <div class="baze-watch-controls-servers">
                @foreach ($providers as $provider)
                    <button
                        type="button"
                        wire:click="switchProvider('{{ $provider['key'] }}')"
                        class="baze-watch-server-btn {{ $activeProvider === $provider['key'] ? 'is-active' : '' }}"
                        aria-pressed="{{ $activeProvider === $provider['key'] ? 'true' : 'false' }}"
                    >
                        {{ 'Server ' . ($loop->index + 1) }}
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Movie Info --}}
    <div class="baze-watch-info">
        <div class="baze-watch-info-main">
            <h1 class="baze-watch-title">{{ $movie['title'] ?? 'Untitled' }}</h1>

            <div class="baze-watch-meta">
                @if (!empty($movie['year']))
                    <span>{{ $movie['year'] }}</span>
                @endif
                @if (!empty($movie['genres']))
                    <span class="baze-watch-meta-dot">·</span>
                    <span>{{ is_array($movie['genres']) ? collect($movie['genres'])->pluck('name')->take(2)->implode(' · ') : $movie['genres'] }}</span>
                @endif
                @if (!empty($movie['runtime']))
                    <span class="baze-watch-meta-dot">·</span>
                    <span>{{ floor($movie['runtime'] / 60) }}h {{ $movie['runtime'] % 60 }}m</span>
                @endif
                @if (isset($movie['vote_average']) && $movie['vote_average'] > 0)
                    <span class="baze-watch-meta-dot">·</span>
                    <span class="baze-watch-score">{{ round($movie['vote_average'] * 10) }}%</span>
                @endif
            </div>

            @if (!empty($movie['overview']))
                <p class="baze-watch-overview">{{ $movie['overview'] }}</p>
            @endif
        </div>

        {{-- Action Buttons --}}
        <div class="baze-watch-actions">
            <button type="button" class="baze-watch-action-btn" wire:click="$toggle('saved')" aria-pressed="{{ ($saved ?? false) ? 'true' : 'false' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                Save
            </button>
            <button type="button" class="baze-watch-action-btn" onclick="navigator.share?.({ title: '{{ addslashes($movie['title'] ?? '') }}', url: window.location.href })">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                Share
            </button>
        </div>
    </div>
</div>
