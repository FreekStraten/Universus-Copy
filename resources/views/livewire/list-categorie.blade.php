<div>
    <x-app-layout>
        <div class="flex justify-end mb-4 pt-5 px-5">
            <button onclick="window.location='{{ url('/categoryCreate') }}'" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-5 rounded">
                {{ __() }} &#43;
            </button>
        </div>

        <div class="bg-gray-200 dark:bg-gray-400 shadow overflow-hidden sm:rounded-md px-10">
            <ul class="divide-y divide-gray-200">
                @foreach ($categories as $category)
                    <li class="hover:bg-gray-300 dark:hover:bg-gray-500" >
                        <div class="px-4 py-4 sm:px-6 flex items-center justify-between">
                            <p class="text-lg font-medium text-indigo-600 truncate">{{ $category->name }}</p>
                            <div class="flex items-center">
                                <a onclick="window.location='{{ url('/categoryEdit', $category->id) }}'" class="cursor-pointer text-blue-500 hover:text-blue-900 p-5">{{ __('Category.Edit') }}</a>
                                <a wire:click="delete({{$category->id}})" dusk="delete" class="cursor-pointer text-red-500 hover:text-red-900">{{ __('Category.Delete') }}</a>
                            </div>
                        </div>
                        <div class="px-4 pb-4 sm:px-6">
                            <p class="text-sm text-black">{{ $category->description }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </x-app-layout>
</div>
