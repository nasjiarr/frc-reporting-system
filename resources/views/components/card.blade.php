<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700']) }}>
    <div class="p-6">
        {{ $slot }}
    </div>
</div>