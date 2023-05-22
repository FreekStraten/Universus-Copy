<div>
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('user.Title') }}
            </h2>
        </x-slot>
        <div class="bg-gray-200 dark:bg-gray-400 shadow overflow-hidden sm:rounded-md px-10">
            <div class="flex border-b-4">
                <div class="w-1/2 px-4 py-4 sm:px-6">
                    <p class="text-lg font-bold text-indigo-600">{{ __('user.Name') }}</p>
                </div>
                <div class="w-1/2 px-4 py-4 sm:px-6">
                    <p class="text-lg font-bold text-indigo-600">{{ __('user.Email') }}</p>
                </div>
                <div class="w-1/2 px-4 py-4 sm:px-6">
                    <p class="text-lg font-bold text-indigo-600">{{ __('user.LastLoggedIn') }}</p>
                </div>
                <div class="w-1/2 px-4 py-4 sm:px-6">
                    <p class="text-lg font-bold text-indigo-600">{{ __('user.ArchivedAt') }}</p>
                </div>
                <div class="w-1/2 px-4 py-4 sm:px-6">
                    <p class="text-lg font-bold text-indigo-600">{{ __('user.Archive/De-archive') }}</p>
                </div>
            </div>
            <ul class="divide-y divide-gray-200">
                @foreach ($users as $user)
                    <li class="hover:bg-gray-300 dark:hover:bg-gray-500 flex">
                        <div class="w-1/2 px-4 py-4 sm:px-6">
                            <p class="text-lg font-medium text-black">{{ $user->name }}</p>
                        </div>
                        <div class="w-1/2 px-4 py-4 sm:px-6">
                            <p class="text-lg font-medium text-black">{{ $user->email }}</p>
                        </div>
                        <div class="w-1/2 px-4 py-4 sm:px-6">
                            @if (!empty($user->last_logged_in))
                                <p class="text-sm text-black">{{ date('d-m-Y', strtotime($user->last_logged_in)) }}</p>
                            @endif
                        </div>
                        <div class="w-1/2 px-4 py-4 sm:px-6">
                            @if (!empty($user->archived_at))
                                <p class="text-sm text-black">{{ date('d-m-Y', strtotime($user->archived_at)) }}</p>
                            @endif
                        </div>
                        @if(is_null($user->archived_at))
                            <div class="w-1/2 px-4 py-4 sm:px-6 flex items-center">
                                <a onclick="window.location='{{ url('/archiveMessage', $user->id) }}'" dusk="archive" class="cursor-pointer text-red-500 hover:text-red-900">
                                <span class="material-symbols-outlined">
                                  Archive
                                </span>
                                </a>
                            </div>
                        @else
                            <div class="w-1/2 px-4 py-4 sm:px-6 flex items-center">
                                <a wire:click="deArchive({{$user->id}})" dusk="dearchive" class="cursor-pointer text-green-500 hover:text-green-900">
                                    <span class="material-symbols-outlined">
                                    Unarchive
                                    </span>
                                </a>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
            {{ $users->links() }}
        </div>
    </x-app-layout>
</div>

