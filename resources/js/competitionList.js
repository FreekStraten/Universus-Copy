// if the deleteCompetitionModal element exists continue, else don't use this file
var modal = document.getElementById("deleteCompetitionModal");


if (modal) {
    // Get the modalCLoseBtn element that closes the modal and add an event listener for a click
    var modalCloseBtn = document.getElementById("modalCloseBtn");
    modalCloseBtn.addEventListener("click", closeModal);

    // get every deleteCompetitionButton element and add an event listener for a click
    var deleteCompetitionButtons = document.getElementsByClassName("modalOpenBtn");


    for (var i = 0; i < deleteCompetitionButtons.length; i++) {
        deleteCompetitionButtons[i].addEventListener("click", function () {
            deleteCompetition(this.id);
        });
    }

    function deleteCompetition(id) {
        //display the modal
        modal.style.display = "block";

        //log id
        console.log("id: " + id);


        // get the input with the id competition_id_input_delete
        var input = document.getElementById("competition_id_input_delete");
        // set the value of the input to the id of the competition
        input.value = id;

    }

    function closeModal() {
        modal.style.display = "none";
    }

}
