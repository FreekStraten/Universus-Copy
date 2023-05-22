<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <h2 class="text-center text-white">{{ __('competition.Competitionheader') }}
                | {{ __('competition.FindACompetitionBody') }}</h2>
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div>
                        <!-- Cards for every category, with the competitions of that category listed under it. -->
                        <div class="">
                            <div class=" d-flex justify-content-center ">
                                @isset($categories)
                                    @Empty($categories)
                                        <div class="">
                                            <p class="text-center text-black m-4">{{__('competition.NoCategoriesFound')}}</p>
                                        </div>
                                    @else
                                        @foreach($categories as $category)
                                            <div class="w-fit lg:max-w-full lg:flex m-2">
                                                <div
                                                    class="border-r border-b border-l border-gray-400 lg:border-l-0 lg:border-t lg:border-gray-400 bg-slate-300 rounded-b lg:rounded-b-none lg:rounded-r p-4 flex flex-col justify-between leading-normal">
                                                    <div class="mb-8">
                                                        <div class="text-gray-900 font-bold text-xl mb-2">
                                                            <h3>{{$category->name}}</h3>
                                                        </div>
                                                        <p class="text-gray-700 text-base">{{$category->description}}</p>
                                                    </div>
                                                    <div class="h-75  m-2 p-2  flex flex-row flex-wrap ">
                                                        @isset($category->competitions)
                                                            @if($category->competitions->isEmpty())
                                                                <div class="">
                                                                    <p class="text-center text-black">{{__('competition.NoCompetitionsFoundInCategory')}}</p>
                                                                </div>
                                                            @endif
                                                            @foreach($category->competitions as $competition)
                                                                @if($competition->archived_at == null)
                                                                    @if($competition->winner == null)

                                                                        <div class="min-w-max m-2  ">
                                                                            <div
                                                                                class="block max-w-sm rounded-lg bg-white p-6 shadow-lg dark:bg-neutral-700">

                                                                                @if(!is_null(Auth::user()) && Auth::user()->isSuperAdmin())
                                                                                    <button
                                                                                        class="modalOpenBtn relative w-10 h-10 overflow-hidden rounded p-2 float-right"
                                                                                        id="{{$competition->id}}"
                                                                                        type="submit"
                                                                                        name="modalOpenBtn">
                                                                                        <div
                                                                                            class="absolute inset-0 bg-red-500 hover:bg-red-700"
                                                                                            id="{{$competition->id}}"
                                                                                            style="opacity: 0.4"></div>
                                                                                        <img
                                                                                            src="{{ asset('images/rubbish.png') }}"
                                                                                            alt="Delete"
                                                                                            class="w-full h-full object-cover">
                                                                                    </button>
                                                                                @endif

                                                                                <h4 class="mb-2 text-xl font-medium leading-tight text-neutral-800 dark:text-neutral-50">
                                                                                <span
                                                                                    class="text-center ">{{$competition->name}}</span>
                                                                                </h4>
                                                                               @if($competition->winner)
                                                                                    <p class="mb-4 text-base font-bold text-black dark:text-neutral-200">
                                                                                    {{ __('CompetitionDetails.Winner', ['name' => $competition->winner_name])}}
                                                                                    </p>
                                                                                @endif
                                                                                <p class="mb-4 text-base text-neutral-600 dark:text-neutral-200">
                                                                                    <div class="mb-4 text-base text-black dark:text-neutral-200">
                                                                                        {!! $competition->description !!}
                                                                                    </div>
                                                                                <p>{{__('competition.StartDate')}}
                                                                                    : {{ \Carbon\Carbon::parse($competition->start_date)->format('d/m/Y')}}</p>
                                                                                <p>{{__('competition.EndDate')}}
                                                                                    : {{ \Carbon\Carbon::parse($competition->end_date)->format('d/m/Y')}}</p>
                                                                                <p>{{__('competition.AmountOfParticipators')}}
                                                                                    : {{$competition->competitorsAmount}}</p>
                                                                                </p>
                                                                                <div class="flex items-stretch">

                                                                                <div class="m-2">
                                                                                    <button
                                                                                        onclick="window.location='{{ url('wedstrijden', $competition->id) }}'"
                                                                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                                                        {{__('competition.Details')}}
                                                                                    </button>
                                                                                </div>
                                                                                <div class="m-2">
                                                                                    @if($competition->userIsParticipating && !Auth::user()->isArchived())
                                                                                        <!-- Button to go to my uploads page -->
                                                                                        <form action="/upload/{{ $competition->id}}" class="competitionId" id="{{ $competition->id}}"
                                                                                              method="GET">
                                                                                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                                                                                    type="submit">
                                                                                                    {{__('CompetitionDetails.Submissions')}}
                                                                                                </button>
                                                                                            </form>

                                                                                        @else
                                                                                            @if(!is_null(Auth::user()) && Auth::user()->isSuperAdmin() ||Auth::user() && Auth::user()->isArchived())
                                                                                                <!-- Admins can't participate in competitions -->
                                                                                            @else
                                                                                            <form
                                                                                                action="{{ route('competitions.participate', ["competitionId" => $competition->id]) }}"
                                                                                                method="POST">
                                                                                                @csrf
                                                                                                <button
                                                                                                    type="submit"
                                                                                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                                                                    {{__('competition.Participate')}}</button>
                                                                                            </form>
                                                                                            @endif
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <h1 class="text-center alert alert-warning text-black">{{__('competition.NoCompetitionsFoundInCategory')}}</h1>
                                                        @endisset
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                @else
                                    <h1 class="text-center text-black">{{__('competition.NoCategoriesFound')}}</h1>
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.CompetitionDeleteModal')

</x-app-layout>
