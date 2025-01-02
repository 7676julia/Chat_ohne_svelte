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


// Funktion zur Überprüfung der Eingaben mit Bootstrap-Validierung
function validateInput(field, isValid, message) {
    if (field.value === "") {
        field.classList.remove("is-invalid", "is-valid");
        removeValidationMessage(field);
    } else if (!isValid) {
        field.classList.add("is-invalid");
        field.classList.remove("is-valid");
        showValidationMessage(field, message, false);
    } else {
        field.classList.add("is-valid");
        field.classList.remove("is-invalid");
        showValidationMessage(field, "Looks good!", true);
    }
}

// Hilfsfunktion zum Anzeigen der Validierungsnachricht
function showValidationMessage(field, message, isValid) {
    let feedback = field.parentElement.querySelector('.valid-feedback, .invalid-feedback');
    if (!feedback) {
        feedback = document.createElement('div');
        feedback.className = isValid ? 'valid-feedback' : 'invalid-feedback';
        field.parentElement.appendChild(feedback);
    }
    feedback.textContent = message;
}

// Hilfsfunktion zum Entfernen der Validierungsnachricht
function removeValidationMessage(field) {
    const feedbacks = field.parentElement.querySelectorAll('.valid-feedback, .invalid-feedback');
    feedbacks.forEach(feedback => feedback.remove());
}

// Benutzername-Validierung
function validateUsername() {
    const username = document.getElementById("username");
    // Prüfen, ob der Benutzername in der Liste der bestehenden Benutzer vorhanden ist
    const isValid = username.value.length >= 3 && !existingUsers.includes(username.value);
    const message = "Der Benutzername ist bereits vergeben oder zu kurz.";
    validateInput(username, isValid, message);
    return isValid;
}

// Passwort-Validierung
function validatePassword() {
    const password = document.getElementById("password");
    const isValid = password.value.length >= 8;
    const message = "Das Passwort muss mindestens 8 Zeichen lang sein.";
    validateInput(password, isValid, message);
    return isValid;
}

// Passwort-Wiederholung validieren
function validatePasswordRepeat() {
    const password = document.getElementById("password");
    const passwordRepeat = document.getElementById("confirm");
    const isValid = passwordRepeat.value === password.value && password.value !== "";
    const message = "Die Passwörter stimmen nicht überein.";
    validateInput(passwordRepeat, isValid, message);
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
        event.preventDefault();
        
        // Bootstrap Form-Validierung aktivieren
        form.classList.add('was-validated');

        // Alle Validierungen durchführen
        const isUsernameValid = validateUsername();
        const isPasswordValid = validatePassword();
        const isPasswordRepeatValid = validatePasswordRepeat();

        // Formular nur absenden wenn alles valid ist
        if (isUsernameValid && isPasswordValid && isPasswordRepeatValid) {
            form.submit();
        }
    });

});

console.log('script.js is loaded');