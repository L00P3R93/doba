<div
    class="baze-search-dropdown"
    x-data="{
        open: @entangle('open'),
        init() {
            $watch('open', (val) => {
                if (val) {
                    this.$nextTick(() => this.$refs.searchInput?.focus());
                }
            });
        }
    }"
    @keydown.escape.window="if (open) { @this.call('close'); open = false; }"
>
    {{-- Trigger --}}
    <button
        type="button"
        class="baze-nav-search"
        aria-label="Search movies and TV shows"
        x-on:click="open = !open; if (!open) { @this.call('close'); }"
        :class="{ 'is-active': open }"
    >
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    </button>

    {{-- Backdrop --}}
    <div
        class="baze-search-backdrop"
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-on:click="open = false; @this.call('close');"
        x-cloak
    ></div>

    {{-- Dropdown panel --}}
    <div
        class="baze-search-panel"
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
        x-cloak
        @click.outside="open = false; @this.call('close');"
    >
        {{-- Input --}}
        <div class="baze-search-panel-input-wrap">
            <svg class="baze-search-panel-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input
                type="search"
                wire:model.live.debounce.300ms="query"
                x-ref="searchInput"
                placeholder="Search movies, TV shows..."
                class="baze-search-panel-input"
                aria-label="Search movies and TV shows"
            />
            @if (!empty($query))
                <button
                    type="button"
                    wire:click="$set('query', '')"
                    class="baze-search-panel-clear"
                    aria-label="Clear search"
                >
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            @endif
        </div>

        {{-- Loading --}}
        <div wire:loading class="baze-search-panel-loading" wire:target="query" aria-live="polite" aria-busy="true">
            <div class="baze-search-panel-spinner"></div>
            <span class="sr-only">Searching...</span>
        </div>

        {{-- Results --}}
        @if (!empty($results) && !$loading)
            <div class="baze-search-panel-results">
                @foreach ($results as $item)
                    <a
                        href="{{ $item['type'] === 'tv' ? route('tv.show', $item['tmdb_id']) : route('watch.movie', $item['tmdb_id']) }}"
                        class="baze-search-panel-result"
                        wire:key="result-{{ $item['tmdb_id'] }}"
                    >
                        <div class="baze-search-panel-result-poster">
                            @if (!empty($item['image']))
                                <img src="{{ $item['image'] }}" alt="" loading="lazy" />
                            @else
                                <div class="baze-search-panel-result-placeholder">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"/><line x1="7" y1="2" x2="7" y2="22"/><line x1="17" y1="2" x2="17" y2="22"/><line x1="2" y1="12" x2="22" y2="12"/><line x1="2" y1="7" x2="7" y2="7"/><line x1="2" y1="17" x2="7" y2="17"/><line x1="17" y1="7" x2="22" y2="7"/><line x1="17" y1="17" x2="22" y2="17"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="baze-search-panel-result-info">
                            <div class="baze-search-panel-result-title">{{ $item['title'] }}</div>
                            <div class="baze-search-panel-result-meta">
                                @if (!empty($item['type']))
                                    <span class="baze-search-panel-result-type">{{ $item['type'] === 'tv' ? 'TV' : 'Movie' }}</span>
                                @endif
                                @if (!empty($item['year']) && $item['year'] !== 'TBA')
                                    <span>{{ $item['year'] }}</span>
                                @endif
                                @if (isset($item['vote_average']) && $item['vote_average'] > 0)
                                    @php $score = (int) round($item['vote_average'] * 10); @endphp
                                    <span class="baze-search-panel-result-score {{ $score >= 70 ? 'baze-search-panel-result-score--high' : ($score >= 50 ? 'baze-search-panel-result-score--mid' : 'baze-search-panel-result-score--low') }}">{{ $score }}%</span>
                                @endif
                            </div>
                        </div>
                        <svg class="baze-search-panel-result-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Empty state: no results --}}
        @if (strlen($query) >= 2 && empty($results) && !$loading)
            <div class="baze-search-panel-empty">
                <p>No results for "{{ $query }}"</p>
            </div>
        @endif

        {{-- Empty state: too short --}}
        @if (strlen($query) > 0 && strlen($query) < 2 && !$loading)
            <div class="baze-search-panel-empty">
                <p>Type at least 2 characters</p>
            </div>
        @endif
    </div>
</div>
