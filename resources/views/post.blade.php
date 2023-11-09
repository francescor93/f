<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">

                <div class="mt-6 text-gray-500">
                    <livewire:show-single-post :item="request()->route('token')" />
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
