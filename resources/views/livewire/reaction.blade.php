<div class="flex basis-[40%]">
    <span class="cursor-pointer basis-2/4 text-center">
        <button wire:click="like"
            class="inline-flex space-x-2 {{ $post->is_liked_by_current_user ? 'text-green-400 hover:text-green-500' : 'text-gray-400 hover:text-gray-500' }} focus:outline-none focus:ring-0">
            <i class="fa-solid fa-thumbs-up"></i>
            <span class="text-xs font-medium">{{ $post->likedByUsers->count() }}</span>
        </button>
    </span>
    <span class="cursor-pointer basis-2/4 text-center">
        <button wire:click="dislike"
            class="inline-flex space-x-2 {{ $post->is_disliked_by_current_user ? 'text-red-400 hover:text-red-500' : 'text-gray-400 hover:text-gray-500' }} focus:outline-none focus:ring-0">
            <i class="fa-solid fa-thumbs-down"></i>
            <span class="text-xs font-medium">{{ $post->dislikedByUsers->count() }}</span>
        </button>
    </span>
</div>
