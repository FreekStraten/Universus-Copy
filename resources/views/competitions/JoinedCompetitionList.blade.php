<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <h2 class="text-center text-white">{{ __('competition.Competitionheader') }} |    {{ __('competition.YourParticipation') }}</h2>
        </h1>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class=" text-gray-900 dark:text-gray-100">
                    <!-- Check boxes for filtering, reload the page with the correct variable in the route parameter set to true. When checkbox changes, submit -->
                    <div>
                        <div class="text-center flex items-center m-4">
                            <form action="{{ route('competitions.participatingList') }}" method="GET" >
                                <div class=" grid lg:grid-cols-2 gap-2 content-start text-black">
                                    <div>
                                        <label class="dark:text-white" for="sort">{{__('competition.FilterByStatus')}}</label>
                                        <select name="sort" onchange="this.form.submit()" class="form-control rounded">
                                            <option value="All" @if($sort === "All") selected @endif >{{__('competition.NoSort')}}</option>
                                            <option value="Closed" @if($sort === "Closed") selected @endif >{{__('competition.Closed')}}</option>
                                            <option value="Finished" @if($sort === "Finished") selected @endif >{{__('competition.Finished')}}</option>
                                            <option value="Ongoing" @if($sort === "Ongoing") selected @endif >{{__('competition.Ongoing')}}</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label  class="dark:text-white" for="ownedSort">{{__('competition.FilterByOwn')}}</label>
                                        <br>
                                        <select name="ownedSort" onchange="this.form.submit()" class="form-control rounded">
                                            <option value="All" @if($ownedSort === "All") selected @endif >{{__('competition.NoSort')}}</option>
                                            <option value="Owned" @if($ownedSort === "Owned") selected @endif >{{__('competition.Owned')}}</option>
                                            <option value="Unowned" @if($ownedSort === "Unowned") selected @endif >{{__('competition.Unowned')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div>

                        <!-- Cards for every category, with the competitions of that category listed under it. -->

                        <div class="">
                            <div class=" d-flex justify-content-center ">
                                @isset($categories)
                                    @Empty($categories)
                                        <div class="m-4 d-flex justify-content-center flex flex-col items-center">
                                            <button class="m-2 text-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                <a href="{{route('competitions.index')}}"> {{__('competition.NoParticipatingCompetitionsFound')}}</a>
                                            </button>
                                        </div>
                                    @else
                                    @foreach($categories as $category)
                                        <div class="w-fit lg:max-w-full lg:flex m-2">
                                            <div class="border-r border-b border-l border-gray-400 lg:border-l-0 lg:border-t lg:border-gray-400 bg-slate-300 rounded-b lg:rounded-b-none lg:rounded-r p-4 flex flex-col justify-between leading-normal">
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
                                                                <button>
                                                                    <a href="{{route('competitions.index')}}"> {{__('competition.NoParticipatingCompetitionsFound')}}</a>
                                                                </button>
                                                                <p class="text-center text-black">{{__('competition.NoParticipatingCompetitionsFound')}}</p>
                                                            </div>
                                                        @endif
                                                        @foreach($category->competitions as $competition)
                                                            <div class="min-w-max m-2  ">
                                                                <div class="block max-w-sm rounded-lg bg-white p-6 shadow-lg dark:bg-neutral-700">
                                                                    <h4 class="mb-2 text-xl font-medium leading-tight text-neutral-800 dark:text-neutral-50">
                                                                        <span class="text-center ">{{$competition->name}}</span>
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

                                                                    <p>{{__('competition.StartDate')}}: {{ \Carbon\Carbon::parse($competition->start_date)->format('d/m/Y')}}</p>
                                                                    <p>{{__('competition.EndDate')}}: {{ \Carbon\Carbon::parse($competition->end_date)->format('d/m/Y')}}</p>
                                                                    <p>{{__('competition.AmountOfParticipators')}}: {{$competition->competitorsAmount}}</p>
                                                                    <br>
                                                                    @if($competition->userIsOwner)
                                                                        <p>{{__('competition.YouAreCompetitionOwner')}}</p>
                                                                    @elseif($competition->hasSubmission)
                                                                        <p>{{__('competition.HasSubmitted')}}</p>
                                                                    @else
                                                                        <p>{{__('competition.HasNotSubmitted')}}</p>
                                                                    @endif


                                                                    <div class="flex items-stretch">
                                                                        <div class="m-2">
                                                                                <button onclick="window.location='{{ url('wedstrijden', $competition->id) }}'" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                                                    {{__('competition.Details')}}</button>
                                                                        </div>
                                                                        @if(!$competition->userIsOwner)
                                                                                <div class="m-2">
                                                                                    <!-- Button to go to my uploads page -->
                                                                                    <form action="/upload/{{ $competition->id}}" class="competitionId" id="{{ $competition->id}}" method="GET">
                                                                                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">
                                                                                            {{__('CompetitionDetails.Submissions')}}
                                                                                        </button>
                                                                                    </form>
                                                                                </div>
                                                                            @endif
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        @else

                                                        <div class="m-4 d-flex justify-content-center flex flex-col items-center">
                                                            <button class="m-2 text-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                                <a href="{{route('competitions.index')}}"> {{__('competition.NoParticipatingCompetitionsFound')}}</a>
                                                            </button>
                                                        </div>
                                                    @endisset
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @endif
                                @else
                                    <div class="m-4 d-flex justify-content-center flex flex-col items-center">
                                        <button class="m-2 text-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            <a href="{{route('competitions.index')}}"> {{__('competition.NoParticipatingCompetitionsFound')}}</a>
                                        </button>
                                    </div>
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
