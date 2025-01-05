// Freundeliste.js - Modified to use Modal for Friend Requests

// Constants and State Management
const REFRESH_INTERVAL = 1000; // 1 second
const TOAST_TYPES = {
    SUCCESS: "success",
    ERROR: "error",
};

// Variable to store the currently selected friend for modal actions
let currentFriendUsername = null;

// Initialize UI components
document.addEventListener("DOMContentLoaded", () => {
    initializeToasts();
    loadInitialData();
    startRefreshCycle();
    setupModalEventListeners();
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
    const friendsElements = document.querySelectorAll("#friends-list .friend-item");
    return Array.from(friendsElements).map((el) => ({
        username: el.getAttribute("data-friend"),
        status: "accepted",
    }));
}

function getFriendshipStatus(username, friendsList) {
    const friend = friendsList.find((f) => f.username === username);
    return friend ? friend.status : "none";
}

function displayAllUsers(users) {
    const usersList = document.getElementById("all-users-list");
    const currentUser = document.body.getAttribute("data-current-user");

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
        if (user === currentUser) return;

        const listItem = document.createElement("div");
        listItem.className = "list-group-item d-flex justify-content-between align-items-center";

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
        const options = { method, headers: {} };

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
        showToast(error.message || "Failed to process request", TOAST_TYPES.ERROR);
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
            Array.from(friendsList.querySelectorAll(".friend-item")).map(
                (item) => [item.getAttribute("data-friend"), item],
            ),
        ),
        requests: new Map(
            Array.from(friendRequests.querySelectorAll(".friend-item")).map(
                (item) => [item.getAttribute("data-friend"), item],
            ),
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
            loadFriends(); // Reload friends list
            document.getElementById("friend-request-name").value = ""; // Clear input
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
                        onclick="removeFriend('${escapeHtml(friend.username)}')">
                    <i class="bi bi-person-x"></i> Remove
                </button>
            </div>
        </div>
    `;
}

function createRequestedFriendHTML(friend) {
    return `
        <div class="d-flex align-items-center justify-content-between friend-request-item w-100 p-3 border rounded mb-2" 
             onclick="openFriendRequestModal('${escapeHtml(friend.username)}')"
             style="transition: all 0.2s ease-in-out; cursor: pointer;">
            <div class="d-flex align-items-center">
                <i class="bi bi-person-plus me-2"></i>
                <span class="friend-name fw-semibold">${escapeHtml(friend.username)}</span>
            </div>
            <div class="d-flex align-items-center">
                <span class="badge bg-warning px-3 py-2">
                    <i class="bi bi-gear me-1"></i>
                    Manage Request
                </span>
            </div>
        </div>
        <style>
            .friend-request-item {
                background-color: #fff;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            }
            .friend-request-item:hover {
                background-color: #f8f9fa;
                transform: translateX(5px);
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            }
            .friend-request-item:active {
                transform: scale(0.98);
            }
            .friend-request-item:hover .badge {
                transform: scale(1.05);
            }
            .friend-request-item .badge {
                transition: all 0.2s ease;
            }
        </style>
    `;
}

// Function to open the friend request modal
function openFriendRequestModal(friendUsername) {
    currentFriendUsername = friendUsername;
    document.getElementById('modalFriendName').textContent = friendUsername;
    const modal = new bootstrap.Modal(document.getElementById('friendRequestModal'));
    modal.show();
}

// Setup event listeners for modal buttons
function setupModalEventListeners() {
    document.getElementById('acceptFriendButton').addEventListener('click', () => {
        if (currentFriendUsername) {
            friendAction('accept', currentFriendUsername);
            currentFriendUsername = null;
            const modal = bootstrap.Modal.getInstance(document.getElementById('friendRequestModal'));
            modal.hide();
        }
    });

    document.getElementById('declineFriendButton').addEventListener('click', () => {
        if (currentFriendUsername) {
            friendAction('decline', currentFriendUsername);
            currentFriendUsername = null;
            const modal = bootstrap.Modal.getInstance(document.getElementById('friendRequestModal'));
            modal.hide();
        }
    });
}