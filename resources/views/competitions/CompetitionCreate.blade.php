<x-app-layout>

    <x-slot name="header">

        <x-head.tinymce-config/>

        <title>Competition Create</title>
    </x-slot>
    
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
        <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <form method="POST" action="{{ route('competitions.store') }}" class="px-6 py-4">
                @csrf
                <div class="mb-3">
                        <label class="text-red-500 font-bold" for="requiredField">
                            {{ __('messages.Required') }}
                        </label>
                        <div class="flex justify-center">
                        <label>
                            <x-input-error :messages="$errors->get('errorMaxPhoto')" class="font-bold ml-2 text-xl"/>
                        </label>
                        </div>
                </div>
                <div class="grid grid-cols-2 gap-4">

                    <!-- Name -->
                    <div class="mb-3 col">
                        <label class="block text-white font-bold mb-2"
                               for="naam">{{ __('messages.CompetitionName') }}
                                <span class="text-red-500">{{ __('messages.RequiredSymbol') }}</span></label>

                        <input
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:text-black"
                            type="text" id="naam" name="naam" value="{{old('naam')}}" required placeholder="{{ __('CompetitionDetails.PlaceholderName') }}" >
                        @error('naam')
                        <div class="text-white border-b-2 border-red-500 mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="mb-3 col">
                        <label class="block text-white font-bold mb-2"
                               for="categorie">{{ __('messages.CompetitionCategory') }}
                                <span class="text-red-500">{{ __('messages.RequiredSymbol') }}</span></label>
                        <select
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:text-black"
                            id="categorie" name="categorie" value="{{old('categorie')}}">
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="block text-white font-bold mb-2"
                           for="omschrijving">{{ __('messages.CompetitionDescription') }}
                           <span class="text-red-500">{{ __('messages.RequiredSymbol') }}</span></label>
{{--                    <textarea--}}
{{--                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:text-black"--}}
{{--                        id="omschrijving" name="omschrijving" rows="5" placeholder="{{ __('CompetitionDetails.PlaceholderDiscription') }}">{{old('omschrijving')}}</textarea>--}}


                    <x-forms.tinymce-editor/>

                    @error('omschrijving')
                    <div class="text-white border-b-2 border-red-500 mt-2">{{ $message }}</div>
                    @enderror
                </div>





                <div class="grid grid-cols-2 gap-4">

                    <!-- Min Amount competitors -->
                    <div class="mb-3">
                        <label class="block text-white font-bold mb-2"
                               for="minimum_aantal_deelnemers">{{ __('messages.CompetitionMinCompetitors') }}
                               <span class="text-red-500">{{ __('messages.RequiredSymbol') }}</span></label>
                        <input
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:text-black"
                            type="number" id="minimum_aantal_deelnemers" name="minimum_aantal_deelnemers"
                            value="{{old('minimum_aantal_deelnemers')}}" required placeholder="{{ __('CompetitionDetails.PlaceholderMinParticipants') }}">
                        @error('minimum_aantal_deelnemers')
                        <div class="text-white border-b-2 border-red-500 mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Max Amount competitors -->
                    <div class="mb-3">
                        <label class="block text-white font-bold mb-2"
                               for="maximum_aantal_deelnemers">{{ __('messages.CompetitionMaxCompetitors') }}</label>
                        <input
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:text-black"
                            type="number" id="maximum_aantal_deelnemers" name="maximum_aantal_deelnemers"
                            value="{{old('maximum_aantal_deelnemers')}}">
                        @error('maximum_aantal_deelnemers')
                        <div class="text-white border-b-2 border-red-500 mt-2">{{ $message }}</div>
                        @enderror
                    </div>


                    <!-- Min Amount photos-->
                    <div class="mb-3">
                        <label class="block text-white font-bold mb-2" for="minimum_aantal_fotos">
                            {{__('competition.MinPhotos')}}
                            <span class="text-red-500">{{ __('messages.RequiredSymbol') }}</span>
                        </label>
                        <input
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:text-black"
                            type="number" id="minimum_aantal_fotos" name="minimum_aantal_fotos"
                            value="{{old('minimum_aantal_fotos')}}" required placeholder="{{ __('CompetitionDetails.PlaceholderMinPhotos') }}">
                        @error('minimum_aantal_fotos')
                        <div class="text-white border-b-2 border-red-500 mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Max Amount photos-->
                    <div class="mb-3">
                        <label class="block text-white font-bold mb-2" for="maximum_aantal_fotos">
                                {{__('competition.MaxPhotos', ['maxPhotos' => $maxPhotos])}}
                        </label>
                        <input
                            class="w-full border {{ $errors->has('errorMaxPhoto') ? 'border-red-500 border-2' : 'border-gray-300' }} rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:text-black"
                            type="number" id="maximum_aantal_fotos" name="maximum_aantal_fotos"
                            value="{{old('maximum_aantal_fotos')}}">
                        @error('maximum_aantal_fotos')
                        <div class="text-white border-b-2 border-red-500 mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Start Date -->
                    <div class="mb-3">
                        <label class="block text-white font-bold mb-2"
                               for="start_datum">{{ __('messages.CompetitionStartDate') }}
                               <span class="text-red-500">{{ __('messages.RequiredSymbol') }}</span></label>
                        <input
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:text-black"
                            type="date" id="start_datum" name="start_datum" value="{{old('start_datum')}}" required>
                        @error('start_datum')
                        <div class="text-white border-b-2 border-red-500 mt-2">{{ $message }}</div>
                        @enderror
                    </div>


                    <!-- End Date -->
                    <div class="mb-3">
                        <label class="block text-white font-bold mb-2"
                               for="eind_datum">{{ __('messages.CompetitionEndDate') }}</label>
                        <input
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:text-black"
                            type="date" id="eind_datum" name="eind_datum" value="{{old('eind_datum')}}">
                        @error('eind_datum')
                        <div class="text-white border-b-2 border-red-500 mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <!-- Submit button -->
                <div class="mb-3 mt-5">
                    <input type="submit" value="{{ __('messages.CompetitionCreate') }}"
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded float-end">
                </div>


            </form>
        </div>
    </div>
</x-app-layout>



