<div>
    <button type="button" id="end-comp-early-confirm-modal-openbtn"
            class="bg-red-500 hover:bg-red-700 text-white font-bold px-2 rounded font-bold uppercase px-8 py-3 my-3">
        {{__('CompetitionDetails.EndCompetitionEarly')}}
    </button>

    <div class="fixed z-10 inset-0 overflow-y-auto" style="display: none;" id="end-comp-early-confirm-modal" role="alertdialog" aria-modal="true" aria-describedby="modalDescription">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all my-8 align-middle max-w-md">
                <div class="bg-white px-4 pt-5 pb-4" >
                    <!-- Modal content goes here -->
                    <p class="text-gray-700" id='modalDescription'>{{__('CompetitionDetails.EndCompetitionEarly')}}?</p>

                </div>
                <div class="bg-gray-50 m-2">
                    <form action="{{ route('competition.end', ["competitionId" => $competition->id]) }}"
                          method="POST">
                        @csrf
                        <button type="button"
                                class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm"
                                id="end-comp-early-confirm-modal-closebtn">
                            {{__('CompetitionDetails.Cancel')}}
                        </button>
                        <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                                >
                            {{__('CompetitionDetails.EndCompetitionEarly')}}
                        </button>
                    </form>

                </div>

            </div>
        </div>
    </div>

</div>
