<div class="fixed z-10 inset-0 overflow-y-auto" style="display: none;" id="WinnerChooseModal">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all my-8 align-middle max-w-md">
            <div class="bg-black text-white px-4 pt-5 pb-4 flex">
                <!-- Modal content goes here -->
                {{__('CompetitionDetails.ChooseWinner')}}
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">

                <button type="button"
                        class="mx-2 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm"
                        id="winner_modalCloseBtn">
                    {{__('CompetitionDetails.Cancel')}}
                </button>

                <input hidden type="text" id="winner_selected_photo_id" value="">
                <!-- Winner button -->
                <button type="button"
                        class="mx-2 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                        id="winner_makeWinnerBtn">
                    {{__('CompetitionDetails.MakeWinner')}}
                </button>

            </div>
        </div>
    </div>
</div>
