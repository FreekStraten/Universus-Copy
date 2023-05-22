<x-app-layout>
    <x-slot name="header">

        <h1 class="font-bold  leading-tight  text-gray-800 dark:text-gray-200 text-center text-5xl mb-2">
            {{__('CompetitionDetails.SubmissionByNameInCompetition', ['name' => $user->name, 'competition' => $competition->name])}}
        </h1>
        <br>
    </x-slot>

    <div class="grid grid-cols-1 gap-1 md:grid-cols-7 lg:grid-cols-10 ">
        <!-- all images, with main photo at top -->
        <div class=" p-3 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg m-2 col-span-6">
            <h2 class="text-center dark:text-white text-xl"> {{__('CompetitionDetails.AllPhotosForThisSubmission')}}</h2>
            <div class="px-4 py-5 sm:px-6 submissionImage dark:text-white justify-center items-center mx-auto flex" id="{{$mainPhotoSubmissionId}}">
                <img src="{{ asset('images/submissions/' . $mainPhotoSubmissionId) }}"
                     alt="{{__('CompetitionDetails.ImageAlt', ['nr' =>  0])}}" class="border border-black border-2 dark:border dark:border-white  h-auto max-w-full h-48 w-96">
            </div>
            <div class="justify-center items-center mx-auto flex">
                <div class="flex flex-wrap">
                    @foreach($submission as $key=>$photo)
                        @if($photo->id == $mainPhotoSubmissionId)
                            @continue
                        @endif
                        <div class="px-4 py-5 sm:px-6 submissionImage dark:text-white" id="{{$photo}}">
                            <img src="{{ asset('images/submissions/' . $photo->id) }}"
                                 alt="{{__('CompetitionDetails.ImageAlt', ['nr' => $key])}}" class="border border-black border-2 dark:border dark:border-white  h-auto max-w-full h-48 w-96">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- div next to it with info as well as the stars -->
        <div class="p-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg m-2 col-span-4">
            <!-- div with info -->
            <div>
                <!-- Currently empty, to be filled with info -->
            </div>
            <!-- div with stars -->
            <div class="">

                <div>
                    @if(is_null(Auth::user()))
                        <div class="flex flex-row-reverse">
                            <a href="{{ route('login') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ">
                                {{__('CompetitionDetails.LoginToGiveFeedback')}}
                            </a>
                        </div>

                    @elseif($userHasGivenFeedback)
                        <p class="text-center dark:text-white text-2xl">{{__('CompetitionDetails.FeedbackGiven')}}</p>
                    @elseif($competition->hasEnded())
                        <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert">
                            <div class="flex">
                                <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                                <div>
                                    <p class="text-sm">  {{__('CompetitionDetails.CompetitionEndedReviewsClosed')}}</p>
                                </div>
                            </div>
                        </div>
                    @elseif($userIsOwner)
                        <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert">
                            <div class="flex">
                                <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                                <div>
                                    <p class="text-sm">  {{__('CompetitionDetails.CannotReviewOwnSubmission')}}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div>
                            <div>
                                <p class="text-center dark:text-white text-xl">{{__('CompetitionDetails.RateThisSubmission')}}</p>
                            </div>
                            <br>
                            <!-- stars -->
                            <div class="flex justify-center">
                                @if(!is_null(Auth::user()) && !Auth::user()->isSuperAdmin())
                                    @for($i=1; $i<=10; $i+=2)
                                        <img src="{{ asset('images/Left_Star.png') }}"
                                             alt="{{ $i/2 }} Sterren"
                                             id="{{ $i }}_{{ $mainPhotoSubmissionId }}"
                                             data-value="0"
                                             class="w-5 h-10"
                                             onclick="updateStars('{{ $mainPhotoSubmissionId }}', {{ $i }})">
                                        <img src="{{ asset('images/Right_Star.png') }}"
                                             alt="{{ $i/2+0.5 }} Sterren"
                                             id="{{ $i+1 }}_{{ $mainPhotoSubmissionId }}"
                                             data-value="0"
                                             class="w-5 h-10"
                                             onclick="updateStars('{{ $mainPhotoSubmissionId }}', {{ $i+1 }})">
                                    @endfor
                                @endif
                            </div>
                            <br>
                            <!-- Text area to give feedback -->
                            <div class="flex justify-center">
                                @if(!is_null(Auth::user()) && !Auth::user()->isSuperAdmin())
                                    <div>
                                        <form method="POST" action="{{ route('postFeedback', ['submissionId' => $mainPhotoSubmissionId, 'competitionId' => $competitionId]) }}" class="px-6 py-4">
                                            @csrf
                                            <input type="hidden" name="picture_id" value="{{ $mainPhotoSubmissionId }}">
                                            <input id="feedbackform_star_rating" type="hidden" name="star_rating" value="0">
                                            <input type="hidden" name="participation_id" value="{{ $participation->participation_id }}">
                                            <textarea  name="feedback" id="feedback" cols="50" rows="10"
                                                      class=" border-2 border-gray-300 p-2 rounded-lg text-black w-full "
                                                      placeholder="{{__('CompetitionDetails.GiveFeedback')}}">{{old('feedback')}}</textarea>
                                            <br>
                                            @error('feedback')
                                            <div class="text-red-500 mt-2 text-sm">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                            <br>
                                            <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert">
                                                <div class="flex">
                                                    <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                                                    <div>
                                                        <p class="text-sm"> {{__('CompetitionDetails.FeedbackRules')}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <button type="submit" class="bg-blue-500 text-white px-4 py-3 rounded font-medium w-full">{{__('CompetitionDetails.SubmitFeedback')}}</button>
                                        </form>
                                    </div>

                                @endif
                            </div>
                        </div>
                    @endif
                    <div>
                        <br>
                        @if(count($feedbackratings) < 1)
                            <p class="text-center dark:text-white text-xl">{{__('CompetitionDetails.NoFeedbackGiven')}}</p>
                        @else
                        <h3 class="text-center dark:text-white text-xl">{{__('CompetitionDetails.FeedbackGivenByOthers')}}</h3>
                        @endif
                        <br>
                    </div>
                    <div class="flex justify-center bg-gray-100 dark:bg-gray-500 p-2">
                        <div class="min-w-full">
                            @foreach($feedbackratings as $feedback)
                                <div class="flex flex-wrap">
                                    @for($i=1; $i<=10; $i++)
                                        @if($i <= $feedback->star_rating)
                                            @if($i % 2 == 0)
                                                <img src="{{ asset('images/Right_Star_Yellow.png') }}"
                                                     alt="{{ $i/2+0.5 }} Sterren"

                                                     data-value="0"
                                                     class="w-5 h-10"
                                                >
                                            @else

                                                <img src="{{ asset('images/Left_Star_Yellow.png') }}"
                                                     alt="{{ $i/2 }} Sterren"
                                                     data-value="0"
                                                     class="w-5 h-10"
                                                >
                                            @endif
                                        @else
                                            @if($i % 2 == 0)
                                                <img src="{{ asset('images/Right_Star.png') }}"
                                                     alt="{{ $i/2+0.5 }} Sterren"

                                                     data-value="0"
                                                     class="w-5 h-10"
                                                >
                                            @else
                                                <img src="{{ asset('images/Left_Star.png') }}"
                                                     alt="{{ $i/2 }} Sterren"
                                                     data-value="0"
                                                     class="w-5 h-10"
                                                >
                                            @endif
                                        @endif
                                    @endfor


                                </div>
                                <div class="m-2">
                                    <p>{{__('CompetitionDetails.FeedbackGivenByOnDate', ['name' => $feedback->feedbackgiver, 'date' => $feedback->date])}} </p>
                                </div>
                                <br>
                                <div class="p-4 sm:px-6 bg-gray-200 dark:text-white dark:bg-gray-600" >
                                    <p class=" dark:text-white text-sm">{{__('CompetitionDetails.Feedback')}}</p>
                                    <div class="dark:text-white text-xl whitespace-pre-wrap">{{$feedback->feedback}}</div>
                                </div>
                                <div class="flex flex-wrap border-b-2 border-black m-2">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>

            @if(Auth::user() && !Auth::user()->isSuperAdmin()) {
            const starValues = {}; // use an object instead of an array
            @foreach($submission as $photo)
                starValues['{{ $photo->id }}'] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            @endforeach
            function updateStars(id, starPartId) {
                const starIndex = parseInt(starPartId) - 1; // convert starPartId to zero-based index
                // update the value of the clicked star and any stars before it
                for (let i = 0; i <= starIndex; i++) {
                    if (i === starIndex) {
                        starValues[id][i] = starValues[id][i] === 1 ? 0 : 1; // toggle between 0 and 1
                    } else {
                        starValues[id][i] = 1; // set all stars before the clicked star to 1
                    }
                }

                //change all the stars after the one that was clicked to 0
                for (let i = starIndex + 1; i < starValues[id].length; i++) {
                    starValues[id][i] = 0;
                }
                updateStarAppreance(id);
                //counts the amount of stars
                let count = 0;
                for (let i = 0; i < starValues[id].length; i++) {
                    if (starValues[id][i] === 1) {
                        count++;
                    }
                }

                // get the input with the id feedbackform_star_rating
                const starRatingInput = document.getElementById('feedbackform_star_rating');
                // set the value of the input to the count
                starRatingInput.value = count;

            }

            function updateStarAppreance($id) {
                // update the appearance of all stars based on their value
                for (let i = 0; i < starValues[$id].length; i++) {
                    const star = document.getElementById(`${i + 1}_${$id}`);
                    if (starValues[$id][i] === 1) {
                        if (i % 2 === 0) {
                            star.src = "{{ asset('images/Left_Star_Yellow.png') }}";
                        } else {
                            star.src = "{{ asset('images/Right_Star_Yellow.png') }}";
                        }
                    } else {
                        if (i % 2 === 0) {
                            star.src = "{{ asset('images/Left_Star.png') }}";
                        } else {
                            star.src = "{{ asset('images/Right_Star.png') }}";
                        }
                    }
                }
            }

            function loadStars() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('getPlayerStarRatingList') }}",
                    data: {
                        "user_id": "{{ Auth::user()->id }}",
                    },
                    beforeSend: function (xhr) {
                        console.log('BeforeSend:', xhr);
                    },
                    success: function (response) {
                        console.log("Request successful", response)
                        //loop through the response and update the starValues
                        for (let i = 0; i < response.length; i++) {
                            const starIndex = parseInt(response[i].star_rating) - 1; // convert starPartId to zero-based index
                            // update the value of the clicked star and any stars before it
                            for (let j = 0; j <= starIndex; j++) {
                                if (j === starIndex) {
                                    starValues[response[i].picture_id][j] = starValues[response[i].picture_id][j] === 1 ? 0 : 1; // toggle between 0 and 1
                                } else {
                                    starValues[response[i].picture_id][j] = 1; // set all stars before the clicked star to 1
                                }
                            }
                            //change all the stars after the one that was clicked to 0
                            for (let j = starIndex + 1; j < starValues[response[i].picture_id].length; j++) {
                                starValues[response[i].picture_id][j] = 0;
                            }
                            updateStarAppreance(response[i].picture_id);
                        }
                    },
                    error: function (error) {
                        console.log("Request error:", error);
                    }
                });
            }

            loadStars();
        }
        @endif

    </script>

</x-app-layout>








