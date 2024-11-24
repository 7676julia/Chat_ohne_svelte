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
        if (friend.status === "accepted");
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

function loadFriends() {
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            let friends = JSON.parse(xmlhttp.responseText);
            console.log(friends);

            handleFriends(friends);
        }
    };
    xmlhttp.open(
        "GET",
        "https://online-lectures-cs.thi.de/chat/605eaf1e-ca25-45bf-8dec-c666f82126a0/friend",
        true,
    );
    xmlhttp.setRequestHeader("Content-type", "application/json");
    xmlhttp.setRequestHeader(
        "Authorization",
        "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyIjoiVG9tIiwiaWF0IjoxNzMxOTI2MzE2fQ.FrdMQhzd_4FyW31CKOQDP3YXY7Tvx1y0plDrwCnfsJM",
    );
    xmlhttp.send();
}

document.addEventListener("DOMContentLoaded", function () {
    loadUsers();
    window.setInterval(function () {
        loadFriends();
    }, 1000);
    loadFriends();
});
