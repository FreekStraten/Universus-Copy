//// End competition early modal confirmation ////

// modal id: end-comp-early-confirm-modal
// cancel id: end-comp-early-confirm-modal-closebtn
// open id: end-comp-early-confirm-modal-openbtn

// Get all elements
const endCompEarlyConfirmModal = document.getElementById("end-comp-early-confirm-modal");
const endCompEarlyConfirmModalCloseBtn = document.getElementById("end-comp-early-confirm-modal-closebtn");
const endCompEarlyConfirmModalOpenBtn = document.getElementById("end-comp-early-confirm-modal-openbtn");

if (endCompEarlyConfirmModal) {

// Open modal
    endCompEarlyConfirmModalOpenBtn.onclick = function() {
        endCompEarlyConfirmModal.style.display = "block";
    }

// Close modal
    endCompEarlyConfirmModalCloseBtn.onclick = function() {
        endCompEarlyConfirmModal.style.display = "none";
    }

// Close modal if clicked outside of it
    window.onclick = function(event) {
        if (event.target == endCompEarlyConfirmModal) {
            endCompEarlyConfirmModal.style.display = "none";
        }
    }
}

