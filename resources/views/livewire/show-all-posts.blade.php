<div wire:poll.3000ms>
    @forelse ($posts as $post)
    <livewire:show-single-post :item="$post" wire:key="{{ $post->id }}" />
    @empty
    <p class="pt-2 text-center text-base font-medium">
        No posts have been published yet
    </p>
    @endforelse
</div>
