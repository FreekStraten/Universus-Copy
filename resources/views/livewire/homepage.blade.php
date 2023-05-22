<x-app-layout>
    <div class="text-black dark:text-white">
        <head>
            <title>Home Page</title>
        </head>
    <div class="bg-gray-300 dark:bg-gray-700">
        <body class="bg-gray-300 dark:bg-gray-700">
            <header class="pb-3 flex justify-center justify-items-center flex-col text-center">
                <div class="mt-2 max-w-7xl mx-auto px-4 sm:px-6 text-black dark:text-white lg:px-8 relative h-60vh w-3/5 flex justify-center flex-col">

                    @if(!is_null(Auth::user()) && Auth::user()->isSuperAdmin())
                        <button
                            wire:click="navigateToBannerUpload()"
                            class="bg-blue-500 hover:bg-blue-700 text-black dark:text-white font-bold py-2 px-4 rounded mt-2 my-1">
                            {{__('Homepage.UploadBanner')}}
                        </button>
                    @endif
                  <img src="{{ asset('images/homepage_banners/'.$bannerId) }}" alt="Banner" class="h-52 object-cover w-full mb-4 block">
                </div>
                <div class="w-full flex justify-center">
                    <h2 class="text-2xl w-7/12 text-center w-fit text-black dark:text-white">{{__('Homepage.SloganTitle')}}</h2>
                </div>
            </header>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 flex justify-center flex-col text-center">
                <p class="text-2xl w-full flex justify-center text-black dark:text-white pb-4">{{__('Homepage.PopulairComp')}}</p>
                <p class="text-2xl font-bold flex justify-center text-black dark:text-white">{{__('Homepage.Participate')}}</p>
                @if($competitionsExisting1)
                <div class="flex flex-row justify-center">
                    <div class="min-w-fit max-w-fit m-2 flex justify-end">
                        <div class=" max-w-sm rounded-lg bg-white p-6 shadow-lg dark:bg-neutral-700">

                            <h4 class="mb-2 text-xl font-medium leading-tight text-neutral-800 dark:text-neutral-50">
                                <span class="text-center ">{{$competition1->name}}</span>
                            </h4>

                            <p class="mb-4 text-base text-neutral-600 dark:text-neutral-200">
                            <p class="text-black dark:text-white">{!! $competition1->description !!}</p>
                            <br>
                            <p class="text-black dark:text-white">{{__('competition.StartDate')}}
                                : {{ \Carbon\Carbon::parse($competition1->start_date)->format('d/m/Y')}}</p>
                            <p class="text-black dark:text-white">{{__('competition.EndDate')}}
                                : {{ \Carbon\Carbon::parse($competition1->end_date)->format('d/m/Y')}}</p>
                            <p class="text-black dark:text-white">{{__('competition.AmountOfParticipators')}}
                                : {{$competitorsAmount1}}</p>
                            </p>
                            <div class="flex items-stretch">

                                <div class="m-2">
                                    <button
                                        onclick="window.location='{{ url('wedstrijden', $competition1->id) }}'"
                                        class="bg-blue-500 hover:bg-blue-700 text-black dark:text-white font-bold py-2 px-4 rounded">
                                        {{__('competition.Details')}}
                                    </button>
                                </div>
                                <div class="m-2">
                                    @if($userIsParticipating1)
                                        <!-- Button to go to my uploads page -->
                                        <form action="/upload/{{ $competition1->id}}" class="competitionId" id="{{ $competition1->id}}"
                                              method="GET">
                                            <button class="bg-blue-500 hover:bg-blue-700 text-black dark:text-white font-bold py-2 px-4 rounded"
                                                    type="submit">
                                                {{__('CompetitionDetails.Submissions')}}
                                            </button>
                                        </form>



                                    @else
                                        <!-- Check if competition is full -->
                                        @if($competitorsAmount1 == $competition1->max_amount_competitors)
                                            <!-- Greyed out and uninteractable button because competition is full -->
                                            <button
                                                class="bg-gray-500 text-black dark:text-white font-bold py-2 px-4 rounded"
                                                disabled>
                                                {{__('competition.Full')}}
                                            </button>
                                        @else

                                            @if(!is_null(Auth::user()) && Auth::user()->isSuperAdmin())
                                                <!-- Admins can't participate in competitions -->
                                            @else
                                                <form
                                                    action="{{ route('competitions.participate', ["competitionId" => $competition1->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                            class="bg-blue-500 hover:bg-blue-700 text-black dark:text-white font-bold py-2 px-4 rounded">
                                                        {{__('competition.Participate')}}</button>
                                                </form>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($competitionsExisting2)
                        <div class="min-w-fit max-w-fit m-2 flex justify-start text-black dark:text-white">
                            <div class="block max-w-sm rounded-lg bg-white p-6 shadow-lg dark:bg-neutral-700">
                                <h4 class="mb-2 text-xl font-medium leading-tight text-neutral-800 dark:text-neutral-50">
                                    <span class="text-center  text-black dark:text-white">{{$competition2->name}}</span>
                                </h4>

                                <p class="mb-4 text-base text-neutral-600 dark:text-neutral-200">
                                <p class="text-black dark:text-white">{!! $competition2->description !!}</p>
                                <br>
                                <p class=" text-black dark:text-white">{{__('competition.StartDate')}}
                                    : {{ \Carbon\Carbon::parse($competition2->start_date)->format('d/m/Y')}}</p>
                                <p class=" text-black dark:text-white">{{__('competition.EndDate')}}
                                    : {{ \Carbon\Carbon::parse($competition2->end_date)->format('d/m/Y')}}</p>
                                <p class=" text-black dark:text-white">{{__('competition.AmountOfParticipators')}}
                                    : {{$competitorsAmount2}}</p>
                                </p>
                                <div class="flex items-stretch">

                                    <div class="m-2">
                                        <button
                                            onclick="window.location='{{ url('wedstrijden', $competition2->id) }}'"
                                            class="bg-blue-500 hover:bg-blue-700  text-black dark:text-white font-bold py-2 px-4 rounded">
                                            {{__('competition.Details')}}
                                        </button>
                                    </div>
                                    <div class="m-2">
                                        @if($userIsParticipating2)
                                            <!-- Button to go to my uploads page -->
                                            <form action="/upload/{{ $competition2->id}}" class="competitionId" id="{{ $competition2->id}}"
                                                  method="GET">
                                                <button class="bg-blue-500 hover:bg-blue-700  text-black dark:text-white font-bold py-2 px-4 rounded"
                                                        type="submit">
                                                    {{__('CompetitionDetails.Submissions')}}
                                                </button>
                                            </form>



                                        @else
                                            <!-- Check if competition is full -->
                                            @if($competitorsAmount2 == $competition2->max_amount_competitors)
                                                <!-- Greyed out and uninteractable button because competition is full -->
                                                <button
                                                    class="bg-gray-500  text-black dark:text-white font-bold py-2 px-4 rounded"
                                                    disabled>
                                                    {{__('competition.Full')}}</button>
                                            @else

                                                @if(!is_null(Auth::user()) && Auth::user()->isSuperAdmin())
                                                    <!-- Admins can't participate in competitions -->
                                                @else
                                                    <form
                                                        action="{{ route('competitions.participate', ["competitionId" => $competition2->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit"
                                                                class="bg-blue-500 hover:bg-blue-700  text-black dark:text-white font-bold py-2 px-4 rounded">
                                                            {{__('competition.Participate')}}</button>
                                                    </form>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($competitionsExisting3)
                    <div class="min-w-fit max-w-fit m-2 flex justify-start text-black dark:text-white">
                        <div class="block max-w-sm rounded-lg bg-white p-6 shadow-lg dark:bg-neutral-700">

                            <h4 class="mb-2 text-xl font-medium leading-tight text-neutral-800 dark:text-neutral-50">
                                <span class="text-center text-black dark:text-white ">{{$competition3->name}}</span>
                            </h4>

                            <p class="mb-4 text-base text-neutral-600 dark:text-neutral-200">
                            <p class="text-black dark:text-white">{!! $competition3->description !!}</p>
                            <br>
                            <p class=" text-black dark:text-white">{{__('competition.StartDate')}}
                                : {{ \Carbon\Carbon::parse($competition3->start_date)->format('d/m/Y')}}</p>
                            <p class=" text-black dark:text-white">{{__('competition.EndDate')}}
                                : {{ \Carbon\Carbon::parse($competition3->end_date)->format('d/m/Y')}}</p>
                            <p class=" text-black dark:text-white">{{__('competition.AmountOfParticipators')}}
                                : {{$competitorsAmount3}}</p>
                            </p>
                            <div class="flex items-stretch">

                                <div class="m-2">
                                    <button
                                        onclick="window.location='{{ url('wedstrijden', $competition3->id) }}'"
                                        class="bg-blue-500 hover:bg-blue-700 text-black dark:text-white font-bold py-2 px-4 rounded">
                                        {{__('competition.Details')}}
                                    </button>
                                </div>
                                <div class="m-2">
                                    @if($userIsParticipating3)
                                        <!-- Button to go to my uploads page -->
                                        <form action="/upload/{{ $competition3->id}}" class="competitionId" id="{{ $competition3->id}}"
                                              method="GET">
                                            <button class="bg-blue-500 hover:bg-blue-700 text-black dark:text-white font-bold py-2 px-4 rounded"
                                                    type="submit">
                                                {{__('CompetitionDetails.Submissions')}}
                                            </button>
                                        </form>
                                    @else
                                        <!-- Check if competition is full -->
                                        @if($competitorsAmount3 == $competition3->max_amount_competitors)
                                            <!-- Greyed out and uninteractable button because competition is full -->
                                            <button
                                                class="bg-gray-500 text-black dark:text-white font-bold py-2 px-4 rounded"
                                                disabled>
                                                {{__('competition.Full')}}</button>
                                        @else

                                            @if(!is_null(Auth::user()) && Auth::user()->isSuperAdmin())
                                                <!-- Admins can't participate in competitions -->
                                            @else
                                                <form
                                                    action="{{ route('competitions.participate', ["competitionId" => $competition3->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                            class="bg-blue-500 hover:bg-blue-700 text-black dark:text-white font-bold py-2 px-4 rounded">
                                                        {{__('competition.Participate')}}</button>
                                                </form>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    </div>
                </div>
            </body>
        </div>
    </div>
</x-app-layout>
