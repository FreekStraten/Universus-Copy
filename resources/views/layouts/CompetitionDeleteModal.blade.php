<!-- Modal -->
<div class="fixed z-10 inset-0 overflow-y-auto" style="display: none;" id="deleteCompetitionModal">
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
            @if(auth()->user() != null && auth()->user()->isSuperAdmin())
                <form method="POST" action="{{ route('deleteCompetition') }}" class="px-6 py-4">
                    @csrf
                    <input type="hidden" name="competition_id" id="competition_id_input_delete" value="-1">
                    @error('competition_id')
                    <div class="text-red-500 mt-2 text-sm">
                        {{ $message }}
                    </div>
                    @enderror
                    <div class="bg-gray-50">
                        <div class="flex justify-center">
                            <input type="text" name="message" id="messageText" class="border-gray-600 rounded-md text-black mb-6" placeholder="{{__('CompetitionDetails.Message')}}">
                        </div>
                        <!-- Delete button -->
                        <button type="button"
                                class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm"
                                id="modalCloseBtn">
                            {{__('CompetitionDetails.Cancel')}}
                        </button>
                        <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                                id="deleteBtn">
                            {{__('CompetitionDetails.Delete')}}
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
