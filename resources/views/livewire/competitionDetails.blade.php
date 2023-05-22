<div> <!-- Data needed in the javascript -->
    <div class="competition_user_id" id="{{$competition->user_id}}"></div>

    @if(!is_null(Auth::user()))
        <div class="player_id" id="{{Auth::user()->id}}"></div>
    @else
        <div class="player_id" id="{{Auth::user()}}"></div>
    @endif

    @if($competition->winner != null)
        <div class="Winner" id="{{$competition->winner}}"></div>
    @endif

    <div class="endDate" id="{{$competition->end_date}}"></div>
</div>

<div>
    <x-app-layout>
        <x-slot name="header">
            <h1 class="font-bold  leading-tight  text-gray-800 dark:text-gray-200 text-center text-5xl mb-2">
                {{($competition->name) }}
            </h1>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-2">
                <div
                    class="block rounded-lg bg-gray-300 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-gray-900">
                    <h2
                        class="text-black dark:text-neutral-200 m-1 mb-2 text-xl font-medium block rounded-lg bg-gray-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-gray-700">
                        <span class="m-2">{{__('messages.CompetitionDescription')}}</span>
                    </h2>
                    <div class="p-2">
                        <div class="mb-4 text-base text-black dark:text-neutral-200">
                            {!! $competition->description !!}
                        </div>
                    </div>
                </div>

                <div
                    class=" block rounded-lg bg-gray-300 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-gray-900">
                    <h4
                        class="text-black dark:text-neutral-200 m-1 mb-2 text-xl font-medium block rounded-lg bg-gray-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-gray-700">
                        <span class="m-2">{{__('messages.Data')}}</span>
                    </h4>
                    <div class="p-2">
                        @if($competition->winner)
                            <p class="mb-4 text-base font-bold text-black dark:text-neutral-200">
                                {{ __('CompetitionDetails.Winner', ['name' => $competition->winner_name])}}
                            </p>
                        @endif
                        <span
                            class="mb-4 text-base text-black dark:text-neutral-200 ">{{__('CompetitionDetails.Category')}}</span>
                        <span class="mb-4 text-base text-black dark:text-neutral-200">
                            {{$competition->category->name}}
                        </span>
                        <br>
                        <br>
                        <p class="mb-4 text-base text-black dark:text-neutral-200">
                            {{__('messages.CompetitionStartDate')}} {{$competition->start_date->toDateString()}}
                            - {{__('messages.CompetitionEndDate')}} {{$competition->end_date->toDateString()}}
                        </p>
                        <p class="mb-4 text-base text-black dark:text-neutral-200">
                            @if($competition->hasEnded())
                                {{__('CompetitionDetails.EndedAgo')}} {{$competition->end_date->diffForHumans()}}
                            @elseif($competition->hasStarted())
                                {{__('CompetitionDetails.EndsIn')}} {{$competition->end_date->diffForHumans()}}
                            @else
                                {{__('CompetitionDetails.StartsIn')}} {{$competition->start_date->diffForHumans()}}
                            @endif
                        </p>
                        <p class="mb-4 text-base text-neutral-700 dark:text-neutral-200">
                        <h5 class="text-black dark:text-gray-200 leading-tight"> {{__('CompetitionDetails.AmountOfParticipants')}} {{ $amountOfParticipating }}
                            / {{$competition->max_amount_competitors}}</h5>
                        <p class="text-black dark:text-gray-200 leading-tight"> {{__('CompetitionDetails.MinimumAmountPhotos')}} {{ $competition->min_amount_pictures }} </p>
                        <p class="text-black dark:text-gray-200 leading-tight"> {{__('CompetitionDetails.MaximumAmountPhotos')}} {{ $competition->max_amount_pictures }} </p>

                    </div>
                </div>
            </div>

            <div class="flex justify-right flex-row-reverse">
                <div class="p-2">
                    @if($loggedInUserIsCompetitionOwner)
                        @if(!$competition->hasEnded())
                            <div class="">

                            </div>
                            @include('components.modals.confirm-end-early-modal')

                        @endif
                    @elseif($loggedInUserIsParticipating && !Auth::user()->isArchived())
                        <form action="/upload/{{ $competitionId}}" class="competitionId" id="{{ $competitionId}}"
                              method="GET">
                            <button
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold px-2 rounded font-bold uppercase px-8 py-3"
                                type="submit">
                                {{__('CompetitionDetails.Submissions')}}
                            </button>
                        </form>
                    @else
                        <!-- Check if competition is full -->
                        @if($competitionIsFull)
                            <!-- Greyed out and uninteractable button because competition is full -->
                            <button class="bg-gray-500 text-white font-bold px-2 rounded font-bold uppercase px-8 py-3"
                                    disabled>
                                {{__('competition.Full')}}</button>
                        @else
                            @if(!is_null(Auth::user()) && Auth::user()->isSuperAdmin() || !is_null(Auth::user()) && Auth::user()->isArchived())
                                <!-- Superadmin doesn't see button  -->
                            @else
                                <form
                                    action="{{ route('competitions.participate', ["competitionId" => $competition->id]) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold px-2 rounded font-bold uppercase px-8 py-3">
                                        @if(Auth::check())
                                            {{__('competition.Participate')}}
                                        @else
                                            {{__('competition.LoginToParticipate')}}
                                        @endif

                                    </button>
                                </form>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
            <br>
            <br>
        </x-slot>


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                            {{__('CompetitionDetails.AllSubmissionsOnCompetition')}}
                        </h2>
                    </div>
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div
                            class="sm:grid sm:grid-cols-1 sm:gap-4 md:grid md:grid-cols-2 md:gap-4 lg:grid lg:grid-cols-3 lg:gap-4">
                            @if(count($mainPictures) == 0)
                                <p class="text-white">{{__('CompetitionDetails.NoWorkSendIn')}}</p>
                            @endif
                            @foreach($mainPictures as $key=>$image)
                                <div
                                    class="bg-gray-300 dark:bg-gray-500 shadow overflow-hidden my-2 sm:rounded-lg p-1 rounded">
                                    @if(!is_null(Auth::user()) && Auth::user()->isSuperAdmin())
                                        <button
                                            class="modalOpenBtn relative w-10 h-10 overflow-hidden rounded p-2 float-right"
                                            id="{{$image->main_photo_id}}" type="submit">
                                            <div class="absolute inset-0 bg-red-500 hover:bg-red-700"
                                                 id="{{$image->main_photo_id}}" style="opacity: 0.4"></div>
                                            <img src="{{ asset('images/rubbish.png') }}" alt="Delete"
                                                 class="w-full h-full object-cover">
                                        </button>
                                    @endif
                                    <div class="sm:px-6 submissionImage max-h-64 min-h-48  "
                                         id="{{$image->main_photo_id}}">
                                        <label>
                                            <input type="submit" name="chooseWinner"
                                                   id="{{$image->main_photo_id}}"
                                                   class="chooseWinner
                                                   inner-input bg-blue-500 hover:bg-blue-700 text-white font-bold px-2 rounded font-bold uppercase px-8 py-3"
                                                   value="{{__("CompetitionDetails.ChooseWinner")}}">
                                        </label>
                                        <div class="text-center">
                                            @if($competition->winner)
                                                <p class="mb-4 text-base font-bold text-black dark:text-neutral-200">
                                                    @if($competition->winner && $image->user_id == $competition->winner)
                                                        {{ __('CompetitionDetails.WinnerCongrats')}}
                                                    @endif
                                                </p>
                                            @endif
                                        </div>
                                        <img src="{{ asset('images/submissions/' . $image->main_photo_id) }}"
                                             alt="{{__('CompetitionDetails.ImageAlt', ['nr' => $key + 1])}}"
                                             class="w-full max-h-48 min-h-40 m-1 object-scale-down">
                                    </div>
                                    <div class="p-1">
                                        <div>

                                            {{trans_choice('CompetitionDetails.SubmittedByNameWithAmountOfPhotos', $image->amount_of_photos , ['name' => $image->user->name, 'amount' => $image->amount_of_photos] )}}
                                        </div>
                                        <div>
                                            @if($image->average_score == -1)
                                                <span>{{__('CompetitionDetails.NoReviewsYet')}}</span>
                                            @else
                                                <span>{{trans_choice('CompetitionDetails.AvgScoreOverAmountOfReviews', $image->amount_of_reviews , ['avg' => $image->average_score / 2, 'amount' => $image->amount_of_reviews] )}}  </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex justify-center m-1">
                                        <div class="flex">
                                            <div>
                                                <div class="flex flex-wrap">
                                                    @for($i=1; $i<=10; $i++)
                                                        @if($i <= $image->average_score)
                                                            @if($i % 2 == 0)
                                                                <img src="{{ asset('images/Right_Star_Yellow.png') }}"
                                                                     alt="{{ $i/2+0.5 }} {{__('CompetitionDetails.Stars')}}"
                                                                     data-value="0"
                                                                     class="w-5 h-10"
                                                                >
                                                            @else
                                                                <img src="{{ asset('images/Left_Star_Yellow.png') }}"
                                                                     alt="{{ $i/2 }} {{__('CompetitionDetails.Stars')}}"
                                                                     data-value="0"
                                                                     class="w-5 h-10"
                                                                >
                                                            @endif
                                                        @else
                                                            @if($i % 2 == 0)
                                                                <img src="{{ asset('images/Right_Star.png') }}"
                                                                     alt="{{ $i/2+0.5 }} {{__('CompetitionDetails.Stars')}}"
                                                                     data-value="0"
                                                                     class="w-5 h-10"
                                                                >
                                                            @else
                                                                <img src="{{ asset('images/Left_Star.png') }}"
                                                                     alt="{{ $i/2 }} {{__('CompetitionDetails.Stars')}}"
                                                                     data-value="0"
                                                                     class="w-5 h-10"
                                                                >
                                                            @endif
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            <a href="{{ route('submissionDetails', ['submissionId' => $image->main_photo_id, 'competitionId' => $competitionId]) }}"
                                               class="m-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded px-4 py-3 ">
                                                {{__('CompetitionDetails.SubmissionDetails')}}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>


                    @include('layouts.WinnerChooseModal')

                    <!-- Modal for deleting a photo -->
                    <div class="fixed z-10 inset-0 overflow-y-auto" style="display: none;" id="myModal">
                        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center">
                            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                            </div>
                            <div
                                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all my-8 align-middle max-w-md">
                                <div class="bg-white px-4 pt-5 pb-4">
                                    <!-- Modal content goes here -->
                                    <p class="text-gray-700">{{__('CompetitionDetails.DeleteWork')}}</p>
                                </div>
                                <div class="flex justify-center">
                                    <input type="text" id="messageText" class="text-black border-gray-600 rounded-md" placeholder="{{__('CompetitionDetails.Message')}}">
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <!-- Delete button -->
                                    <button type="button"
                                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                                            id="deleteBtn">
                                        {{__('CompetitionDetails.Delete')}}
                                    </button>
                                    <button type="button"
                                            class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm"
                                            id="modalCloseBtn">
                                        {{__('CompetitionDetails.Cancel')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript -->
        <script>
            // Delete modal

            const modalOpenBtns = document.getElementsByClassName("modalOpenBtn");
            const modalCloseBtn = document.getElementById("modalCloseBtn");
            const modal = document.getElementById("myModal");
            const wedstrijdId = {{ $competitionId }};

            for (let i = 0; i < modalOpenBtns.length; i++) {

                modalOpenBtns[i].addEventListener("click", function (event) {
                    modal.style.display = "block";
                    const deleteBtn = document.getElementById("deleteBtn");
                    deleteBtn.addEventListener("click", function () {
                        let xhr = new XMLHttpRequest();
                        const messageText = document.getElementById("messageText");
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                window.location.href = '/wedstrijden/' + wedstrijdId;
                            } else if (xhr.readyState === 4 && xhr.status !== 200) {
                                console.log(xhr);
                            }
                        };
                        xhr.open('POST', '/wedstrijden/' + event.target.id);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                        xhr.send("message=" + messageText.value);
                    });

                });
            }

            modalCloseBtn.addEventListener("click", function () {
                modal.style.display = "none";
            });
        </script>
    </x-app-layout>
</div>
