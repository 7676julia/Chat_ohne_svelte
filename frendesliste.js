//Blatt 4 php
// Funktion zum Laden der Freundesliste
function loadFriends() {
    fetch('ajax_load_friends.php')
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Error loading friends');
            }
        })
        .then(friends => {
            handleFriends(friends);
        })
        .catch(error => console.error('Error:', error));
}

function loadUsers() {
    fetch('ajax_load_users.php')
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Error loading users');
            }
        })
        .then(data => {
            console.log(data);
            suggestions(data);
        })
        .catch(error => console.error('Error:', error));
}

function addFriend() {
    const friendInput = document.getElementById("friend-request-name");
    const friendName = friendInput.value.trim();

    fetch('ajax_add_friend.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ username: friendName })
    })
    .then(response => {
        if (response.ok) {
            friendInput.value = '';
            loadFriends(); // Refresh friends list
        } else {
            // Handle error (e.g., red border)
            friendInput.style.borderColor = "red";
        }
    })
    .catch(error => {
        console.error('Error:', error);
        friendInput.style.borderColor = "red";
    });
}

function suggestions(users) {
    const suggestionsBox = document.getElementById("friend-selector");
    // Clear existing options
    suggestionsBox.innerHTML = '';
    
    // Get current user (you might need to adjust how this is determined)
    const currentUser = "Tom"; // Replace with actual method of getting current user

    users.forEach((friend) => {
        // Skip current user
        if (friend !== currentUser) {
            const option = document.createElement("option");
            option.value = friend;
            option.textContent = friend;
            suggestionsBox.appendChild(option);
        }
    });
}



function handleFriends(friends) {
    const friendsList = document.getElementById("friends-list");
    const friendRequests = document.getElementById("friend-requests");

    friendsList.innerHTML = "";
    friendRequests.innerHTML = "";

    for (var friend of friends) {
        if (friend.status === "accepted")
        {
            let listItem = document.createElement("li");
            let link = document.createElement("a");

            link.setAttribute(
                "href",
                "chat.html?friend=" + encodeURIComponent(friend.username),
            );
            link.textContent = friend.username;

            listItem.appendChild(link);
            friendsList.appendChild(listItem);
        }

        if (friend.status === "requested") {
            let listItem = document.createElement("li");
            listItem.textContent = friend.username;

            friendRequests.appendChild(listItem);
        }
    }
}

document.addEventListener("DOMContentLoaded", function () {
    loadUsers();
    window.setInterval(function () {
        loadFriends();
    }, 1000);
    loadFriends();
});


