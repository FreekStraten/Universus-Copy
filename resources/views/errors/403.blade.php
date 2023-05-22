<x-app-layout>

    <x-slot name="header">
        <title>403</title>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-red-500 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("errors.403") }}
                </div>
            </div>
        </div>
    </div>

    <!-- Go back button to previous page-->
    <div class="flex items-center justify-center">
        <a href="{{ url()->previous() }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            {{ __("messages.GoBack") }}
        </a>
    </div>

</x-app-layout>
