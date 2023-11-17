<x-app-layout>

    <div class="relative">
        <div
            class="relative bg-cover bg-center bg-no-repeat bg-fixed h-28 md:h-40 before:content-[''] before:absolute before:top-0 before:left-0 before:right-0 before:bottom-0 before:bg-[#21283759]"
            style="background-image:url('{{ $user->header_photo_url }}')">
        </div>
        <div class="relative block md:flex md:px-12 items-end">
            <div class="w-16 md:w-44 h-16 md:h-44 ml-5 md:ml-0 rounded-lg -mt-14 md:-mt-36 bg-white">
                <img class="rounded-md w-full max-w-full" src="{{ $user->profile_photo_url }}" alt="Profile Pic" />
            </div>

            <div class="flex flex-col md:hidden text-sm">
                <h4>{{ $user->name }}</h4>
                <div class="font-weight-600 mb-1">
                    {{ '@' . $user->username }}
                </div>
                @if ($user->bio)
                <p class="text-center">
                    {{ $user->bio }}
                </p>
                @endif
                <div class="flex justify-center my-1">
                    @if ($user->location)
                    <div class="w-1/2 whitespace-nowrap overflow-hidden text-ellipsis text-right">
                        <i class="fa fa-map-marker-alt fa-fw"></i>&nbsp;{{ $user->location }}
                    </div>
                    @endif
                    @if ($user->location && $user->website)
                    &nbsp;|&nbsp;
                    @endif
                    @if ($user->website)
                    <div class="w-1/2 whitespace-nowrap overflow-hidden text-ellipsis text-left">
                        <i class="fa fa-link fa-fw"></i>&nbsp;<a href="{{ $user->website }}" target="_blank">{{
                            $user->website }}</a>
                    </div>
                    @endif
                </div>
            </div>

            <ul
                class="overflow-x-auto md:overflow-x-hidden relative text-sm flex flex-1 md:ml-12 border-t-2 md:border-t-0 border-b-2 border-[#c9d2e3]">
                <li class="basis-full">
                    <a href="#profile-post" class="block pt-3.5 px-2.5 pb-2 text-center" data-toggle="tab">
                        <div class="nav-field">{{ __('Posts') }}</div>
                        <div class="nav-value">{{ $user->sentPosts->count() }}</div>
                    </a>
                </li>
                <li class="basis-full">
                    <a href="#profile-media" class="block pt-3.5 px-2.5 pb-2 text-center" data-toggle="tab">
                        <div class="nav-field">{{ __('Media') }}</div>
                        <div class="nav-value">{{ $user->attachments->count() }}</div>
                    </a>
                </li>
                <li class="basis-full">
                    <a href="#profile-followers" class="block pt-3.5 px-2.5 pb-2 text-center" data-toggle="tab">
                        <div class="nav-field">{{ __('Followers') }}</div>
                        <div class="nav-value">{{ $user->followers->count() }}</div>
                    </a>
                </li>

                <li class="basis-full">
                    <a href="#profile-followers" class="block pt-3.5 px-2.5 pb-2 text-center" data-toggle="tab">
                        <div class="nav-field">{{ __('Following') }}</div>
                        <div class="nav-value">{{ $user->following->count() }}</div>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="flex">
        <div class="hidden md:block md:w-48 lg:w-56">
            <h4>{{ $user->name }}</h4>
            <div class="font-weight-600 mb-3">
                {{ '@' . $user->username }}
            </div>
            @if ($user->bio)
            <p>
                {{ $user->bio }}
            </p>
            @endif
            @if ($user->location)
            <div class="mb-1">
                <i class="fa fa-map-marker-alt fa-fw"></i>&nbsp;{{ $user->location }}
            </div>
            @endif
            @if ($user->website)
            <div class="mb-3">
                <i class="fa fa-link fa-fw"></i>&nbsp;<a href="{{ $user->website }}" target="_blank">{{ $user->website
                    }}</a>
            </div>
            @endif
            <hr class="mt-4 mb-4" />
        </div>

        <div class="mt-5 md:pl-7 flex-1">
            <livewire:create-post />
            <livewire:show-all-posts source="profile" :user="$user" class="mt-5" />
        </div>
    </div>

</x-app-layout>
