<div>
    @if ($show)
    <x-button class="justify-center w-full" wire:click="toggle">
        {{ $buttonText }}
    </x-button>
    @endif
</div>
