//Data
const winner = document.querySelector('.Winner');
const player_id = document.querySelector('.player_id').id;
const competition_user_id = document.querySelector('.competition_user_id').id;
const endDate = document.querySelector('.endDate').id;

// Input fields
const winnerInputs = document.querySelectorAll('input[name="chooseWinner"]');


// Modal
const WinnerChooseModal = document.querySelector('#WinnerChooseModal');
const winnerButton = document.querySelector('#winner_makeWinnerBtn');
const closeButton = document.querySelector('#winner_modalCloseBtn');

if (!player_id === null || competition_user_id !== player_id || winner || (Date.parse(endDate) > Date.now())) {
    disableWinnerButtons();
}


winnerInputs.forEach(function (input) {
    input.addEventListener('click', function (e) {
        WinnerChooseModal.style.display = 'block';
        document.querySelector('#winner_selected_photo_id').value = e.target.id;

    });
});

closeButton.addEventListener('click', function () {
    WinnerChooseModal.style.display = 'none';
});


winnerButton.addEventListener('click', function (e) {

    if (competition_user_id === player_id) {

        let main_photo_id = document.querySelector('input[id="winner_selected_photo_id"]').value;
        console.log(main_photo_id);

        fetch('/makeWinner/' + main_photo_id, {
            method: 'POST',
            body: JSON.stringify({
                main_photo_id: main_photo_id
            }),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        WinnerChooseModal.style.display = 'none';

        disableWinnerButtons();

    } 
});

function disableWinnerButtons() {
    winnerInputs.forEach(function (input) {
            input.style.display = 'none';
            input.disabled = true;
        }
    );
}



