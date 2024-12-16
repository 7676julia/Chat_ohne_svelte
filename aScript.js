// Variable für die Benutzernamen, die vom Server zurückgegeben werden
let existingUsers = [];

// XMLHttpRequest, um die Benutzernamen vom Server abzurufen
const xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
        let data = JSON.parse(xmlhttp.responseText);
        existingUsers = data; // Benutzerliste speichern
        console.log("Benutzerliste vom Server:", existingUsers);
    }
};

// Chat Server URL und Collection ID als Teil der URL
xmlhttp.open("GET", window.backendUrl + "/user", true);

// Das Token zur Authentifizierung
xmlhttp.setRequestHeader('Authorization', 'Bearer ' + window.token);

// Anfrage senden
xmlhttp.send();


// Funktion zur Überprüfung der Eingaben
function validateInput(field, isValid) {
    if (field.value === "") {
        field.classList.remove("invalid", "valid");
    } else if (!isValid) {
        field.classList.add("invalid");
        field.classList.remove("valid");
    } else {
        field.classList.add("valid");
        field.classList.remove("invalid");
    }
}

// Benutzername-Validierung
function validateUsername() {
    const username = document.getElementById("username");
    // Prüfen, ob der Benutzername in der Liste der bestehenden Benutzer vorhanden ist
    const isValid = username.value.length >= 3 && !existingUsers.includes(username.value);
    validateInput(username, isValid);
    return isValid;
}

// Passwort-Validierung
function validatePassword() {
    const password = document.getElementById("password");
    const isValid = password.value.length >= 8;
    validateInput(password, isValid);
    return isValid;
}

// Passwort-Wiederholung validieren
function validatePasswordRepeat() {
    const password = document.getElementById("password");
    const passwordRepeat = document.getElementById("confirm");
    const isValid = passwordRepeat.value === password.value && password.value !== "";
    validateInput(passwordRepeat, isValid);
    return isValid;
}

// Event-Listener für die Eingabefelder
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById("registerForm");
    if (!form) {
        console.error("Formular nicht gefunden!");
        return;
    }

    document.getElementById("username").addEventListener("input", validateUsername);
    document.getElementById("password").addEventListener("input", validatePassword);
    document.getElementById("confirm").addEventListener("input", validatePasswordRepeat);

    // Event-Listener für das Formular
    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Verhindert das Absenden des Formulars

        // Alle Validierungen durchführen
        const isUsernameValid = validateUsername();
        const isPasswordValid = validatePassword();
        const isPasswordRepeatValid = validatePasswordRepeat();

        // Formular nur absenden wenn alles valid ist
        if (isUsernameValid && isPasswordValid && isPasswordRepeatValid) {
            form.submit();
        }
    });
    // Formular-Validierung
    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent form submission
        validateForm(event); // Run the centralized validation logic
    });


});

console.log('script.js is loaded');