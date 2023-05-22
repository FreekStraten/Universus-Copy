<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('competition.LoadPicture') }}
        </h1>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="relative pt-1">
                        <div class="flex mb-2 items-center justify-between">
                            <div>
                                <span class="text-m font-semibold inline-block text-black dark:text-white">
                                    {{ __('messages.ProgressBarRemainingPhotos', ['submitted' => $userSubmittedTotal, 'total' => $competition->max_amount_pictures]) }}
                                </span>
                            </div>
                        </div>
                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-blue-200">
                            <div style="width:{{ $userSubmittedTotal/$competition->max_amount_pictures*100 }}%"
                                 class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500"></div>
                        </div>

                        @if($userSubmittedTotal < $competition->min_amount_pictures)
                            <div class="text-right">
                                <p class="text-xs text-red-600 mb-2">
                                    {{ __('messages.ProgressBarMinPhotosWarning', ['remaining' => $competition->min_amount_pictures - $userSubmittedTotal]) }}
                                </p>
                            </div>
                        @endif
                    </div>


                    @if(!$competition->hasStarted())
                        <p class="text-center text-xl"> {{__('CompetitionDetails.CompetitionNotStarted')}} </p>
                    @elseif($competition->hasEnded())
                        <div class=" bg-red-500 text-white py-2 px-4 font-bold rounded text-2xl text-center m-2">
                            <span>
                                 {{__('CompetitionDetails.CompetitionEnded')}}
                            </span>
                        </div>
                    @elseif($userUploadedMaxReached)
                        <p class="text-center text-xl"> {{__('CompetitionDetails.MaximumAmountReached')}} </p>
                    @else

                        <p class="hidden"> {{__('CompetitionDetails.CompetitionMinAndMaxPhotoReminder', ['min' => $competition->min_amount_pictures, 'max' => $competition->max_amount_pictures])}} </p>
                        <br>
                        <form action="{{route("pictures.store", $id)}}" method="POST" enctype="multipart/form-data"
                              class="flex">
                            @csrf
                            <input type="file" name="image" accept="image/jpeg">
                            <button
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold px-2 rounded text-center ml-auto"
                                type="submit">{{__('CompetitionDetails.UploadSubmission')}}</button>
                        </form>
                    @endif

                    @if(session('message'))
                        <div class="alert alert-success">
                            {{ __('CompetitionDetails.'.session('message')) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($userSubmittedTotal > 0)

        <!-- Big header seperator with text indicating the user that the next images are his/her own -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h1 class=" text-3xl text-center">{{__('CompetitionDetails.YourOwnSubmissions')}}</h1>
            </div>
        </div>


        <!-- Smaller header indicating the user should pick which photo he/she wants to submit -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="text-xl text-center">{{__('CompetitionDetails.MakeSubmissionMain')}}</h2>
                <h3 class="text-sm text-center">{{__('CompetitionDetails.MakeSubmissionMainExplanation')}}</h3>
            </div>
        </div>


        <!-- uploaded images from user -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="sm:grid sm:grid-cols-1 sm:gap-4 md:grid md:grid-cols-2 md:gap-4 lg:grid lg:grid-cols-3 lg:gap-4">
                    <!--Form submits the currently selected photo (radio buttons) to web route -->

                    @csrf
                    @if(count($userPictures) == 0)
                        <p class="text-white">{{__('CompetitionDetails.NoWorkSendIn')}}</p>
                    @endif

                    @foreach($userPictures as $key=>$userPicture)
                        @foreach(File::allFiles(public_path('images/submissions/')) as $image)
                            @if($userPicture->id == $image->getRelativePathname())
                                <div class="bg-gray-400 shadow overflow-hidden sm:rounded-lg p-2">

                                    @if($userPicture->id == $image->getRelativePathname())
                                        <!-- if the image is the main image, show a checkmark -->
                                        @if($userPicture->id == $mainImageId)
                                            <!-- Image is main photo, so show a checkmark -->
                                            <div class="flex bg-green-500 text-white py-2 px-4 font-bold rounded text-2xl">
                                                <span class="text-green-900">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mt-0.5 mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </span>
                                                {{__('CompetitionDetails.ThisSubmissionIsAlreadyMain')}}
                                            </div>
                                        @else
                                            @if(!$competition->hasEnded())
                                            <form action="{{route("picture.updateMain", $userPicture->id)}}"
                                                  method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="image_id" value="{{$userPicture->id}}">
                                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-2xl w-full" type="submit">
                                                    {{__('CompetitionDetails.MakeThisSubmissionMain')}}
                                                </button>
                                            </form>
                                            @endif
                                        @endif
                                    @endif

                                    @if(!is_null(Auth::user()) && Auth::user()->isSuperAdmin())
                                        <button
                                            class="modalOpenBtn relative w-10 h-10 overflow-hidden rounded p-2 float-right"
                                            id="{{$userPicture->id}}" type="submit">
                                            <div class="absolute inset-0 bg-red-500 hover:bg-red-700"
                                                 id="{{$userPicture->id}}" style="opacity: 0.4"></div>
                                            <img src="{{ asset('images/rubbish.png') }}" alt="Delete"
                                                 class="w-full h-full object-cover">
                                        </button>
                                    @endif
                                    <div class="px-4 py-5 sm:px-6">
                                        <img src="{{ asset('images/submissions/' . $image->getRelativePathname()) }}"
                                             alt="{{__('CompetitionDetails.ImageAlt', ['nr' => $key + 1])}}"
                                             class="w-full p-2">
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>

    @endif
</x-app-layout>
