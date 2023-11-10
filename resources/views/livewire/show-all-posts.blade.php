<div wire:poll.3000ms>
    @foreach ($posts as $post)
    <livewire:show-single-post :item="$post" wire:key="{{ $post->id }}" />
    @endforeach
</div>
