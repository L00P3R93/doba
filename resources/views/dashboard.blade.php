<x-layouts::app :title="__('Dashboard')">
    <div id="main-content" class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-6">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-amber-500/10 to-amber-600/5 rounded-xl border border-amber-500/20 p-6">
            <h2 class="text-2xl font-bold text-amber-400 mb-2">
                Welcome back, {{ auth()->user()->name ?? 'User' }}!
            </h2>
            <p class="text-gray-400">
                Here's what's happening with your account today.
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-neutral-800/50 to-neutral-900/50 p-6 hover:border-amber-500/30 transition-all duration-300">
                <div class="flex flex-col h-full justify-between">
                    <div>
                        <div class="w-8 h-8 text-amber-400 mb-2 text-3xl">🎵</div>
                        <h3 class="text-lg font-semibold text-white">Total Tracks</h3>
                    </div>
                    <div class="text-3xl font-bold text-amber-400">0</div>
                </div>
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-neutral-800/50 to-neutral-900/50 p-6 hover:border-amber-500/30 transition-all duration-300">
                <div class="flex flex-col h-full justify-between">
                    <div>
                        <div class="w-8 h-8 text-amber-400 mb-2 text-3xl">▶️</div>
                        <h3 class="text-lg font-semibold text-white">Total Plays</h3>
                    </div>
                    <div class="text-3xl font-bold text-amber-400">0</div>
                </div>
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-neutral-800/50 to-neutral-900/50 p-6 hover:border-amber-500/30 transition-all duration-300">
                <div class="flex flex-col h-full justify-between">
                    <div>
                        <div class="w-8 h-8 text-amber-400 mb-2 text-3xl">💰</div>
                        <h3 class="text-lg font-semibold text-white">Earnings</h3>
                    </div>
                    <div class="text-3xl font-bold text-amber-400">KES 0</div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-neutral-800/50 to-neutral-900/50 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-white">Recent Activity</h3>
                <flux:button variant="primary" size="sm">View All</flux:button>
            </div>
            <div class="space-y-4">
                <div class="flex items-center gap-4 p-4 rounded-lg bg-neutral-900/50 border border-neutral-700">
                    <div class="w-5 h-5 text-amber-400 text-xl">📊</div>
                    <div class="flex-1">
                        <p class="text-white font-medium">No recent activity</p>
                        <p class="text-gray-400 text-sm">Start uploading your music to see activity here</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
