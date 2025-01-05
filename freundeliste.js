// Constants and State Management
const REFRESH_INTERVAL = 1000; // 2 seconds
const TOAST_TYPES = {
    SUCCESS: "success",
    ERROR: "error",
};

// Initialize UI components
document.addEventListener("DOMContentLoaded", () => {
    initializeToasts();
    loadInitialData();
    startRefreshCycle();
});

function loadInitialData() {
    loadUsers();
    loadFriends();
    loadAllUsers();
}

function loadAllUsers() {
    fetch("ajax_load_users.php")
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((users) => {
            displayAllUsers(users);
        })
        .catch((error) => {
            console.error("Error loading users:", error);
            const usersList = document.getElementById("all-users-list");
            usersList.innerHTML = `
            <div class="list-group-item text-danger">
                Error loading users. Please try again later.
            </div>
        `;
        });
}

function getCurrentFriendsList() {
    const friendsElements = document.querySelectorAll(
        "#friends-list .friend-item",
    );
    return Array.from(friendsElements).map((el) => {
        return {
            username: el.getAttribute("data-friend"),
            status: "accepted",
        };
    });
}

function getFriendshipStatus(username, friendsList) {
    const friend = friendsList.find((f) => f.username === username);
    if (!friend) return "none";
    return friend.status;
}

function displayAllUsers(users) {
    const usersList = document.getElementById("all-users-list");
    const currentUser = document.body.getAttribute("data-current-user");

    // Get current friends list to check friendship status
    const friendsList = getCurrentFriendsList();

    usersList.innerHTML = "";

    if (users.length === 0) {
        usersList.innerHTML = `
            <div class="list-group-item text-muted text-center">
                No users found
            </div>
        `;
        return;
    }

    users.forEach((user) => {
        // Skip current user
        if (user === currentUser) return;

        const listItem = document.createElement("div");
        listItem.className =
            "list-group-item d-flex justify-content-between align-items-center";

        // Check if user is already a friend
        const friendshipStatus = getFriendshipStatus(user, friendsList);

        let actionButton = "";
        switch (friendshipStatus) {
            case "none":
                actionButton = `
                    <button class="btn btn-sm btn-outline-primary" onclick="sendFriendRequest('${user}')">
                        <i class="bi bi-person-plus"></i> Add Friend
                    </button>
                `;
                break;
            case "pending":
                actionButton = `
                    <span class="badge bg-warning">Request Pending</span>
                `;
                break;
            case "accepted":
                actionButton = `
                    <span class="badge bg-success">Already Friends</span>
                `;
                break;
        }

        listItem.innerHTML = `
            <div class="d-flex align-items-center">
                <span>${user}</span>
            </div>
            ${actionButton}
        `;

        usersList.appendChild(listItem);
    });
}

function startRefreshCycle() {
    window.setInterval(() => {
        loadFriends();
        loadAllUsers();
    }, REFRESH_INTERVAL);
}

// Toast Management
function initializeToasts() {
    const toastElList = Array.from(document.querySelectorAll(".toast"));
    toastElList.forEach((toast) => new bootstrap.Toast(toast));
}

function showToast(message, type = TOAST_TYPES.SUCCESS) {
    const toast = document.getElementById("requestSentToast");
    if (!toast) {
        alert(message);
        return;
    }

    const toastBody = toast.querySelector(".toast-body");
    const icon = toast.querySelector(".toast-header i");
    if (!toastBody || !icon) {
        alert(message);
        return;
    }

    toastBody.textContent = message;
    icon.className = `bi ${
        type === TOAST_TYPES.SUCCESS
            ? "bi-check-circle-fill text-success"
            : "bi-x-circle-fill text-danger"
    } me-2`;

    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
}

// API Functions
async function makeRequest(url, method = "GET", data = null) {
    try {
        const options = {
            method,
            headers: {},
        };

        if (data) {
            if (data instanceof FormData) {
                options.body = data;
            } else {
                options.headers["Content-Type"] = "application/json";
                options.body = JSON.stringify(data);
            }
        }

        const response = await fetch(url, options);

        if (response.status === 401) {
            window.location.href = "login.php";
            return null;
        }

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const contentType = response.headers.get("content-type");
        if (contentType && contentType.includes("application/json")) {
            return await response.json();
        }

        return await response.text();
    } catch (error) {
        console.error("Request failed:", error);
        throw error;
    }
}

// Data Loading Functions
async function loadFriends() {
    try {
        const friends = await makeRequest("ajax_load_friends.php");
        if (friends) {
            handleFriends(friends);
        }
    } catch (error) {
        console.log("Failed to load friends list:", error);
        showToast("Failed to load friends list", TOAST_TYPES.ERROR);
    }
}

async function loadUsers() {
    try {
        const users = await makeRequest("ajax_load_users.php");
        if (users) {
            updateUserSelector(users);
        }
    } catch (error) {
        console.log("Failed to load users:", error);
        showToast("Failed to load users", TOAST_TYPES.ERROR);
    }
}

// Friend Actions
function updateUserSelector(users) {
    const dataList = document.getElementById("friend-selector");
    if (!dataList) return;

    dataList.innerHTML = "";
    users.forEach((user) => {
        // Skip the current user
        if (document.body.getAttribute("data-current-user") === user) return;
        const option = document.createElement("option");
        option.value = user;
        dataList.appendChild(option);
    });
}

async function friendAction(action, friend) {
    const formData = new FormData();
    formData.append("action", action);
    formData.append("friend", friend);

    try {
        await makeRequest("ajax_friend_action.php", "POST", formData);
        showToast(`Friend ${action} successful!`);
        await loadFriends();
    } catch (error) {
        showToast(
            error.message || "Failed to process request",
            TOAST_TYPES.ERROR,
        );
    }
}

function removeFriend(friendUsername) {
    if (
        !confirm(
            `Are you sure you want to remove ${friendUsername} from your friends?`,
        )
    ) {
        return;
    }
    friendAction("remove", friendUsername);
}

// UI Update Functions
function handleFriends(friends) {
    if (typeof friends === "string") {
        try {
            friends = JSON.parse(friends);
        } catch (e) {
            console.error("Could not parse 'friends' as JSON:", e);
            return;
        }
    }

    const friendsList = document.getElementById("friends-list");
    const friendRequests = document.getElementById("friend-requests");

    if (!friendsList || !friendRequests) return;
    if (!Array.isArray(friends)) {
        console.error("Expected 'friends' to be an array, got:", friends);
        return;
    }

    const existingElements = {
        friends: new Map(
            Array.from(friendsList.querySelectorAll(".friend-item"))
                .map((item) => [item.getAttribute("data-friend"), item]),
        ),
        requests: new Map(
            Array.from(friendRequests.querySelectorAll(".friend-item"))
                .map((item) => [item.getAttribute("data-friend"), item]),
        ),
    };

    friends.forEach((friend) => {
        const container = friend.status === "accepted"
            ? friendsList
            : friendRequests;
        const existing = friend.status === "accepted"
            ? existingElements.friends.get(friend.username)
            : existingElements.requests.get(friend.username);

        updateFriendElement(friend, container, existing);

        if (friend.status === "accepted") {
            existingElements.friends.delete(friend.username);
        } else {
            existingElements.requests.delete(friend.username);
        }
    });

    // Clean up removed elements
    existingElements.friends.forEach((item) => item.remove());
    existingElements.requests.forEach((item) => item.remove());
}

const sendFriendRequest = (friendUsername) => {
    const formData = new FormData();
    formData.append("action", "add");
    formData.append("friend", friendUsername);

    fetch("ajax_friend_action.php", {
        method: "POST",
        body: formData,
    })
        .then((response) => {
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
        .catch((error) => {
            console.error("Error sending friend request:", error.message);
            alert("Failed to send friend request. Please try again.");
        });
};

function updateFriendElement(friend, container, existingElement) {
    const shouldUpdate = !existingElement ||
        existingElement.getAttribute("data-unread") !== String(friend.unread);

    if (!shouldUpdate) return;

    const newElement = createFriendElement(friend);

    if (existingElement) {
        container.replaceChild(newElement, existingElement);
    } else {
        container.appendChild(newElement);
    }
}

function createFriendElement(friend) {
    const element = document.createElement("div");
    element.className = "friend-item";
    element.setAttribute("data-friend", friend.username);
    element.setAttribute("data-unread", friend.unread || "0");

    const username = encodeURIComponent(friend.username);

    element.innerHTML = friend.status === "accepted"
        ? createAcceptedFriendHTML(friend, username)
        : createRequestedFriendHTML(friend);

    return element;
}

// Event Listeners
document.getElementById("send-request-button")?.addEventListener(
    "click",
    function (event) {
        event.preventDefault();
        const friendInput = document.getElementById("friend-request-name");
        const friendUsername = friendInput?.value.trim();

        if (!friendUsername) {
            showToast("Please enter a username", TOAST_TYPES.ERROR);
            return;
        }

        friendAction("add", friendUsername)
            .then(() => {
                if (friendInput) friendInput.value = "";
            });
    },
);

// Utility Functions
function escapeHtml(unsafe) {
    if (!unsafe) return "";
    return unsafe
        .toString()
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// HTML Template Functions
function createAcceptedFriendHTML(friend, username) {
    return `
        <div class="d-flex align-items-center justify-content-between w-100">
            <div class="d-flex align-items-center">
                <a href="chat.php?friend=${username}" class="friend-name">
                    ${escapeHtml(friend.username)}
                </a>
                ${
        friend.unread
            ? `<span class="unread-badge ms-2">${friend.unread}</span>`
            : ""
    }
            </div>
            <div class="friend-actions ms-3">
                <a href="profil.php?friend=${username}" 
                   class="btn btn-outline-secondary btn-sm me-2">
                    <i class="bi bi-person"></i> Profile
                </a>
                <button class="btn btn-outline-primary btn-sm me-2" 
                        onclick="location.href='chat.php?friend=${username}'">
                    <i class="bi bi-chat-dots"></i> Chat
                </button>
                <button class="btn btn-outline-danger btn-sm" 
                        onclick="removeFriend('${
        escapeHtml(friend.username)
    }')">
                    <i class="bi bi-person-x"></i> Remove
                </button>
            </div>
        </div>
    `;
}

function createRequestedFriendHTML(friend) {
    return `
        <div class="d-flex align-items-center justify-content-between">
            <span class="friend-name">${escapeHtml(friend.username)}</span>
            <div class="friend-actions">
                <button class="btn btn-success btn-sm" 
                        onclick="friendAction('accept', '${
        escapeHtml(friend.username)
    }')">
                    <i class="bi bi-check-lg"></i> Accept
                </button>
                <button class="btn btn-danger btn-sm" 
                        onclick="friendAction('dismiss', '${
        escapeHtml(friend.username)
    }')">
                    <i class="bi bi-x-lg"></i> Reject
                </button>
            </div>
        </div>
    `;
}
