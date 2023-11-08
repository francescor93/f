<x-form-section submit="save" gap="gap-0">

    <x-slot name="form">

        <div class="col-span-6 sm:col-span-4">
            <x-label for="privacy" value="{{ __('Privacy') }}" />
            <x-select id="privacy" class="block mt-1 w-full" name="privacy" wire:model="privacy" required autofocus>
                <option value="" wire:key="none" selected disabled>{{ __('Choose privacy') }}</option>
                <option value="0" wire:key="privacy-0">{{ __('All') }}</option>
                <option value="1" wire:key="privacy-1">{{ __('Registered users') }}</option>
                <option value="2" wire:key="privacy-2">{{ __('Only me') }}</option>
            </x-select>
            <x-input-error for="privacy" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="body" value="{{ __('Body') }}" />
            <x-textarea id="body" class="block mt-1 w-full" name="body" wire:model="body" required />
            <x-input-error for="body" class="mt-2" />
        </div>

    </x-slot>

    <x-slot name="actions">
        <x-button type="submit" wire:loading.attr="disabled">
            Save
            <div wire:loading>
                <?xml version="1.0" encoding="UTF-8" standalone="no"?><svg xmlns:svg="http://www.w3.org/2000/svg"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0"
                    width="16px" height="16px" viewBox="0 0 128 128" xml:space="preserve">
                    <g>
                        <circle cx="16" cy="64" r="16" fill="#000000" />
                        <circle cx="16" cy="64" r="16" fill="#555555" transform="rotate(45,64,64)" />
                        <circle cx="16" cy="64" r="16" fill="#949494" transform="rotate(90,64,64)" />
                        <circle cx="16" cy="64" r="16" fill="#cccccc" transform="rotate(135,64,64)" />
                        <animateTransform attributeName="transform" type="rotate"
                            values="0 64 64;315 64 64;270 64 64;225 64 64;180 64 64;135 64 64;90 64 64;45 64 64"
                            calcMode="discrete" dur="960ms" repeatCount="indefinite"></animateTransform>
                    </g>
                </svg>
            </div>
        </x-button>
    </x-slot>

</x-form-section>
