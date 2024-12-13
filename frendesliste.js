
function suggestions(users) {
    const suggestionsBox = document.getElementById("friend-selector");
    // eventlistener für Änderungen im Eingabefeld-->
    users.forEach((friend) => {
        const li = document.createElement("option");
        li.textContent = friend;
        suggestionsBox.appendChild(li);
    });
}

function loadUsers() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            let data = JSON.parse(xmlhttp.responseText);
            console.log(data);
            suggestions(data);
        }
    };
    xmlhttp.open(
        "GET",
        "https://online-lectures-cs.thi.de/chat/605eaf1e-ca25-45bf-8dec-c666f82126a0/user",
        true,
    );
    // Add token, e. g., from Tom
    xmlhttp.setRequestHeader(
        "Authorization",
        "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyIjoiVG9tIiwiaWF0IjoxNzMxOTI2MzE2fQ.FrdMQhzd_4FyW31CKOQDP3YXY7Tvx1y0plDrwCnfsJM",
    );
    xmlhttp.send();
}

function handleFriends(friends) {
    const friendsList = document.getElementById("friends-list");
    const friendRequests = document.getElementById("friend-requests");

    friendsList.innerHTML = "";
    friendRequests.innerHTML = "";

    for (var friend of friends) {
        if (friend.status === "accepted") {
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
            //mehr verzweiflung

            // Create form for Accept button
            let acceptForm = document.createElement("form");
            acceptForm.method = "POST"; // Set method to POST
            acceptForm.action = "freundeliste.php"; // Change this to your PHP file path

            // Create hidden input for the friend's username -> damit man weiß welche freundeanfrage angenommen wird
            let usernameInput = document.createElement("input");
            usernameInput.type = "hidden";
            usernameInput.name = "username"; // Set the name for PHP access
            usernameInput.value = friend.username; // Set the value to the friend's username

            // Create Accept button
            let acceptButton = document.createElement("button");
            acceptButton.textContent = "Accept";
            acceptButton.type = "submit"; // Change to submit to send the form
            acceptButton.name = "aktion"; // Set name for PHP access
            acceptButton.value = "akzeptieren"; // Set value for the action




            // Create Reject button
            let rejectButton = document.createElement("button");
            rejectButton.textContent = "Reject";
            rejectButton.onclick = function () {
                // Assuming similar implementation for friendDismiss
                if ($service.friendDismiss(friend.username)) {
                    loadFriends(); // Reload friends if successful
                } else {
                    console.error("Failed to dismiss friend request");
                    // Optionally, show an error message to the user
                }
            };

            // Append buttons to list item
            listItem.appendChild(acceptButton);
            listItem.appendChild(rejectButton);
            friendRequests.appendChild(listItem);
        }
    }
}


//load friends with ajax
function loadFriends() {
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4) {
            if (xmlhttp.status == 200) {
                let friends = JSON.parse(xmlhttp.responseText);
                console.log(friends);

                handleFriends(friends);
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


document.addEventListener("DOMContentLoaded", function () {
    loadUsers();
    window.setInterval(function () {
        loadFriends();
    }, 1000);
    loadFriends();
});