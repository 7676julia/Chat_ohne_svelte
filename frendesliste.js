document.addEventListener("DOMContentLoaded", () => {
    loadUsers(); // Benutzer laden
    loadFriends(); // Freunde laden

    // Lade Freunde alle 1 Sekunde
    window.setInterval(loadFriends, 1000);
});

// Funktion zur Verarbeitung der Freunde
function handleFriends(friends) {
    const friendsList = document.getElementById("friends-list");
    const friendRequests = document.getElementById("friend-requests");

    friendsList.innerHTML = "";
    friendRequests.innerHTML = "";

    friends.forEach(friend => {
        if (friend.status === "accepted") {
            let listItem = document.createElement("li");
            let link = document.createElement("a");

            link.setAttribute(
                "href",
                "chat.php?friend=" + encodeURIComponent(friend.username),
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
    });
}


// Lade Freunde mit Ajax
function loadFriends() {
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4) {
            if (xmlhttp.status == 200) {
                let friends = JSON.parse(xmlhttp.responseText);
                console.log(friends);
                handleFriends(friends); // Stelle sicher, dass diese Funktion definiert ist
            } else if (xmlhttp.status == 401) {
                console.error("Not authorized");
            } else {
                console.error("Failed to load friends");
            }
        }
    };
    xmlhttp.open("GET", "ajax_load_friends.php", true);
    xmlhttp.send();
}

//nutzer laden
function loadUsers() {
    fetch("ajax_load_users.php", {
        method: "GET",
        headers: {
            "Content-Type": "application/json"
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json(); // JSON-Daten parsen
    })
    .then(data => {
        console.log("Users loaded successfully:", data);

        // Hier kannst du die Benutzerliste in die HTML-Seite integrieren
        const userListContainer = document.getElementById("user-list");
        userListContainer.innerHTML = ""; // Vorherigen Inhalt löschen

        data.forEach(user => {
            const userItem = document.createElement("div");
            userItem.className = "user-item";
            userItem.textContent = user.name; // Angenommen, `name` ist ein Feld im JSON-Objekt
            userListContainer.appendChild(userItem);
        });
    })
    .catch(error => {
        console.error("Error loading users:", error.message);
    });
}


// Funktion zum Ausführen von Freundschaftsaktionen
async function friendAction(action, friend) {
    try {
        const response = await fetch('ajax_friend_action.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ action, friend })
        });

        if (response.ok) {
            alert(`Action "${action}" for friend "${friend}" was successful!`);
            loadFriends(); // Aktualisiert die UI dynamisch
        } else {
            const error = await response.json();
            alert(`Error: ${error.message}`);
        }
    } catch (err) {
        console.error("Request failed", err);
    }
}

// Funktion zum Erstellen von Buttons für Freundesaktionen
function createFriendActionButtons(friend, listItem) {
    const actions = {
        requested: [
            { text: "Accept", action: "accept" },
            { text: "Reject", action: "dismiss" }
        ],
        accepted: [
            { text: "Remove", action: "remove" }
        ]
    };

    const buttons = actions[friend.status] || [];
    buttons.forEach(({ text, action }) => {
        const button = document.createElement("button");
        button.textContent = text;
        button.onclick = () => friendAction(action, friend.username);
        listItem.appendChild(button);
    });
}

document.getElementById("send-request-button").addEventListener("click", function (event) {
    event.preventDefault(); // Prevent form submission

    const friendRequestName = document.getElementById("friend-request-name").value;
    if (!friendRequestName) {
        alert("Please enter a friend name!");
        return;
    }

    sendFriendRequest(friendRequestName);
});

const sendFriendRequest = (friendUsername) => {
    const formData = new FormData();
    formData.append('action', 'add');
    formData.append('friend', friendUsername);

    fetch('ajax_friend_action.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error: ${response.status}`);
        }
        return response.text();
    })
    .then(() => {
        alert(`Friend request sent to ${friendUsername}`);
        loadFriends(); // Freundesliste neu laden
        document.getElementById("friend-request-name").value = ""; // Input-Feld leeren
    })
    .catch(error => {
        console.error('Error sending friend request:', error.message);
        alert('Failed to send friend request. Please try again.');
    });
};



/*//add friend button event listener
document.getElementById("send-request-button").addEventListener("click", function () {
    const friendNameInput = document.getElementById("friend-request-name");
    const friendName = friendNameInput.value.trim(); // Get the friend's name

    if (!friendName) return; // Exit if no name is provided

    // Send AJAX request to add the friend
    fetch("freundeliste.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            action: "add",
            friend: friendName,
        }),
    });

    // Clear the input field after sending the request
    friendNameInput.value = "";
});
*/