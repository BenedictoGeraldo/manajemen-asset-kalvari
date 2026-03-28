<div class="bg-white border-b border-gray-200 px-6 py-4">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800 ml-12 md:ml-0">@yield('page-title', 'Dashboard')</h2>
        <div class="flex items-center space-x-3">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-medium text-gray-800">{{ Auth::user()->name ?? 'Administrator' }}</p>
                <p class="text-xs text-gray-500">{{ Auth::user()->email ?? '' }}</p>
            </div>
            <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                <span class="text-sm font-semibold text-gray-700">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</span>
            </div>
        </div>
    </div>
</div>
