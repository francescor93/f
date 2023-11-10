<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">

                <div class="mt-6 text-gray-500">
                    <livewire:create-post />
                </div>

                <div class="mt-6 text-gray-500">
                    <livewire:show-all-posts source="home" />
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
