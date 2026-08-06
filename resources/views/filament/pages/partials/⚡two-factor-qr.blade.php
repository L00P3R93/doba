<div class="space-y-4">
    <div class="flex justify-center">
        <div class="relative flex items-center justify-center w-56 h-56 p-3 bg-white border rounded-lg border-gray-200 dark:border-white/10">
            @if ($qrCodeSvg)
                <div class="bg-white rounded p-2">
                    {!! $qrCodeSvg !!}
                    <p class="mt-4 text-xs break-all">
                        {{ auth()->user()->twoFactorQrCodeUrl() }}
                    </p>
                </div>
            @else
                <span class="text-sm text-gray-400 dark:text-gray-500">Loading QR code&hellip;</span>
            @endif
        </div>
    </div>

    <div class="relative flex items-center justify-center w-full">
        <div class="absolute inset-0 w-full h-px top-1/2 bg-gray-200 dark:bg-white/10"></div>
        <span class="relative px-2 text-xs bg-white dark:bg-gray-900 text-gray-500 dark:text-gray-400">
            or enter the code manually
        </span>
    </div>

    <div class="flex justify-center">
        @if ($manualSetupKey)
            <code class="px-3 py-2 text-sm rounded-lg select-all bg-gray-100 dark:bg-white/5">
                {{ $manualSetupKey }}
            </code>
        @else
            <span class="text-sm text-gray-400 dark:text-gray-500">Loading setup key&hellip;</span>
        @endif
    </div>
</div>
