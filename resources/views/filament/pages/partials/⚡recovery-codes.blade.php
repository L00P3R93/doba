<div class="space-y-3">
    @if (filled($codes))
        <div
            class="grid gap-1 p-4 font-mono text-sm rounded-lg bg-gray-100 dark:bg-white/5"
            role="list"
            aria-label="Recovery codes"
        >
            @foreach ($codes as $code)
                <div role="listitem" class="select-text">{{ $code }}</div>
            @endforeach
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400">
            Each recovery code can be used once and will be removed after use. Regenerate if you run low.
        </p>
    @else
        <p class="text-sm text-gray-500 dark:text-gray-400">
            No recovery codes are available. Try regenerating them.
        </p>
    @endif
</div>
