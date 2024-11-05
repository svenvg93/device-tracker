<x-guest-layout :title="__('Server Error')">
    <div class="grid px-4 min-h-dvh place-content-center">
        <div class="text-center">
            <h1 class="font-black text-gray-200 dark:text-gray-800 text-9xl">500</h1>

            <p class="text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-100 sm:text-4xl">{{ __('Oops, server error!') }}</p>

            <p class="mt-4 text-gray-500 dark:text-gray-300">There was an issue, check the logs or view the docs for help.</p>

        </div>
    </div>
</x-guest-layout>
