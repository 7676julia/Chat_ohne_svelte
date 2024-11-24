// Dummy-Datensatz der existierenden Nutzer
const existingUsers = ["Alice", "Bob", "Charlie"];

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
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById("registerForm");
    if (!form) {
        console.error("Formular nicht gefunden!");
        return;
    }

    document.getElementById("username").addEventListener("input", validateUsername);
    document.getElementById("password").addEventListener("input", validatePassword);
    document.getElementById("confirm").addEventListener("input", validatePasswordRepeat);

    // Event-Listener für das Formular
    form.addEventListener("submit", function(event) {
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
function validateForm(event) {
    const isUsernameValid = validateUsername();
    const isPasswordValid = validatePassword();
    const isPasswordRepeatValid = validatePasswordRepeat();

    if (!isUsernameValid || !isPasswordValid || !isPasswordRepeatValid) {
        event.preventDefault(); // Verhindert das Absenden des Formulars
    }
}

});

console.log('script.js is loaded');