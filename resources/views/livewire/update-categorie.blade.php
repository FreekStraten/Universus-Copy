<div>
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Category.Update') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-white">
                        <form wire:submit.prevent="editCategory({{$category->id}})" class="max-w-md mx-auto">
                            <div class="mb-4">
                                <label class="block font-bold mb-2">
                                    {{ __('Category.Name') }}
                                </label>
                                <input wire:model="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" dusk="name" type="text" placeholder="{{ __('Naam') }}">
                                @error('name') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block font-bold mb-2">
                                    {{ __('Category.Description') }}
                                </label>
                                <textarea wire:model="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" dusk="description" placeholder="{{ __('Beschrijving') }}" rows="3"></textarea>
                                @error('description') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>


                            <div class="flex items-center justify-center">
                                <button dusk="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    {{ __('Category.Submit') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</div>
