<div class="md:flex flex-col bg-gray-50 rounded-xl p-8 pt-4 mb-6 dark:bg-gray-600">
    <div class="flex flex-col">
        <div class="flex">
            <div class="mr-2 text-xs md:text-sm font-medium">
                @if ($post->privacy == "0")
                <i class="fa-solid fa-globe"></i>
                @elseif ($post->privacy == "1")
                <i class="fa-solid fa-user-group"></i>
                @elseif ($post->privacy == "2")
                <i class="fa-solid fa-user"></i>
                @endif
            </div>
            <div class="text-sm font-medium">
                {{ $post->sender->name }}
                @if ($post->sender->id != $post->recipient->id)
                &nbsp;>&nbsp;{{ $post->recipient->name}}
                @endif
            </div>
        </div>
        <div class="mb-4 text-xs font-medium">
            <a href="{{ route('post', ['token' => $post->token]) }}">
                @if (!$post->created_at->isToday())
                {{ $post->created_at->format('j M Y')}},
                @endif
                {{ $post->created_at->format('H:i') }}
            </a>
        </div>
    </div>
    <div class="flex md:ml-2 mb-2">
        <img class="w-10 h-10 md:w-20 md:h-20 rounded-full mr-4 md:mr-8 object-cover"
            src="{{ $post->sender->profile_photo_url }}" alt="{{ $post->sender->name }}" width="200" height="200">
        <div class="pt-2 space-y-4">
            <p class="text-base font-medium">
                {{ $post->body }}
            </p>
            @if ($post->attachments->count())
            <hr />
            @foreach($post->attachments as $attachment)
            <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" class="inline-block">
                <img src="{{ asset('storage/' . $attachment->file_path) }}" alt="Attachment"
                    class="w-32 h-32 object-cover">
            </a>
            @endforeach
            @endif
        </div>
    </div>
</div>
