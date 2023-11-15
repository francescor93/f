@props(['submit', 'gap' => "gap-6"])

<div {{ $attributes->merge(['class' => 'lg:grid lg:grid-cols-4 xl:grid-cols-3 lg:gap-3']) }}>
    @if ((isset($title)) || (isset($description)))
    <x-section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-section-title>
    @endif

    <div class="lg:col-span-3 xl:col-span-2">
        <form wire:submit="{{ $submit }}">
            <div
                class="px-4 py-5 bg-white dark:bg-gray-800 sm:p-6 shadow {{ isset($actions) ? 'sm:rounded-tl-md sm:rounded-tr-md' : 'sm:rounded-md' }}">
                <div class="grid grid-cols-6 {{ $gap }}">
                    {{ $form }}
                </div>
            </div>

            @if (isset($actions))
            <div
                class="flex items-center px-4 py-3 bg-gray-50 dark:bg-gray-800 text-end sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                {{ $actions }}
            </div>
            @endif
        </form>
    </div>
</div>
