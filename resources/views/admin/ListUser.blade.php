<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('user.Title') }}
        </h2>
    </x-slot>

  <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="table-auto border border-gray-400 dark:border border-white" >
                        <thead>
                            <tr>
                                <th class="border border-white-800 px-4 py-2">{{ __('user.Name') }}</th>
                                <th class="border border-white-800 px-4 py-2">{{ __('user.Email') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!is_null(Auth::user()) && Auth::user()->isSuperAdmin())
                            @foreach ($users as $user)
                                <tr>
                                    <td class="border border-white-800 px-4 py-2">{{ $user->name }}</td>
                                    <td class="border border-white-800 px-4 py-2">{{ $user->email }}</td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
   </div>

</x-app-layout>
