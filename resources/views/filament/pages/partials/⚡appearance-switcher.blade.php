@php
    $tabs = [
        [
            'value' => 'light',
            'label' => 'Light',
            'icon' => 'heroicon-o-sun',
        ],
        [
            'value' => 'dark',
            'label' => 'Dark',
            'icon' => 'heroicon-o-moon',
        ],
        [
            'value' => 'system',
            'label' => 'System',
            'icon' => 'heroicon-o-computer-desktop',
        ],
    ];
@endphp

<div class="fi-section-content rounded-xl p-4 dark:bg-gray-900">
    <div
        x-data="{
        appearance: localStorage.getItem('theme') ?? 'system',

        init() {
            this.applyTheme(this.appearance);

            window.matchMedia('(prefers-color-scheme: dark)')
                .addEventListener('change', () => {
                    if (this.appearance === 'system') {
                        this.applyTheme('system');
                    }
                });
        },

        applyTheme(value) {
            this.appearance = value;

            localStorage.setItem('theme', value);

            const isDark =
                value === 'dark' ||
                (value === 'system' &&
                    window.matchMedia('(prefers-color-scheme: dark)').matches);

            document.documentElement.classList.toggle('dark', isDark);

            // Optional: if using Livewire, notify parent component
            $dispatch('appearance-changed', {
                appearance: value,
            });
        },
    }"
        class="inline-flex items-center gap-1 rounded-lg bg-neutral-100 p-1 dark:bg-neutral-800"
    >
        @foreach ($tabs as $tab)
            <button
                type="button"
                @click="applyTheme('{{ $tab['value'] }}')"
                :class="appearance === '{{ $tab['value'] }}'
                ? 'bg-white text-neutral-900 shadow-xs dark:bg-neutral-700 dark:text-neutral-100'
                : 'text-neutral-500 hover:bg-neutral-200/60 hover:text-neutral-900 dark:text-neutral-400 dark:hover:bg-neutral-700/60 dark:hover:text-neutral-100'"
                class="flex items-center rounded-md px-3.5 py-1.5 text-sm font-medium transition-all duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-neutral-900"
            >
                <x-dynamic-component
                    :component="$tab['icon']"
                    class="-ml-0.5 h-4 w-4 shrink-0"
                />

                <span class="ml-1.5">
                {{ $tab['label'] }}
            </span>
            </button>
        @endforeach
    </div>
</div>

