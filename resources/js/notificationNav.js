// get the element with notifications_open_button id
const notifications_open_button = document.getElementById("notifications_open_button");
const notification_amount_popup_bell = document.getElementById("notification_amount_popup_bubble");

// Mark as read when notifications are opened
if (notifications_open_button) {
    notifications_open_button.addEventListener("click", function () {
        markNotificationsAsRead();
    });
}

function markNotificationsAsRead() {
    fetch('/markAsRead', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    });

    // if notification_amount_popup_bubble exists, set it to hidden
    if (notification_amount_popup_bell) {
        notification_amount_popup_bell.style.visibility = "hidden";
    }
}
