<?php
require("start.php");

// Check if user is logged in
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Load current user data
$currentUser = $service->loadUser($_SESSION['user']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new Model\User($_SESSION['user']);
    $user->setFirstName(htmlspecialchars($_POST['firstName'] ?? ''));
    $user->setLastName(htmlspecialchars($_POST['lastName'] ?? ''));
    $user->setCoffeeOrTea(htmlspecialchars($_POST['beverages'] ?? ''));
    $user->setDescription(htmlspecialchars($_POST['description'] ?? ''));
    $user->setChatLayout(htmlspecialchars($_POST['chatLayout'] ?? ''));
    $user->addToHistory(); // Add timestamp to change history

    if ($service->saveUser($user)) {
        header("Location: freundeliste.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings</title>
    <link rel="stylesheet" href="app.css">
</head>
<body>
    <h1 class="left">Profile Settings</h1>
    <form action="einstellungen.php" method="post">
        <fieldset>
            <legend>Base Data</legend>
            <div class="field">
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="firstName" placeholder="Your Name" 
                    value="<?= htmlspecialchars($currentUser->getFirstName() ?? '') ?>">
            </div>
            <div class="field">
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="lastName" placeholder="Your Surname"
                    value="<?= htmlspecialchars($currentUser->getLastName() ?? '') ?>">
            </div>
            <div class="field">
                <label for="beverages">Coffee or Tea?</label>
                <select id="beverages" name="beverages">
                    <?php
                    $options = ['Neither nor', 'Coffee', 'Tea', 'Both'];
                    $currentChoice = $currentUser->getCoffeeOrTea();
                    foreach ($options as $option) {
                        $selected = ($currentChoice === $option) ? 'selected' : '';
                        echo "<option value=\"$option\" $selected>$option</option>";
                    }
                    ?>
                </select>
            </div>
        </fieldset>

        <fieldset>
            <legend>Tell Something About You</legend>
            <textarea name="description" rows="6" cols="110"><?= htmlspecialchars($currentUser->getDescription() ?? '') ?></textarea>
        </fieldset>

        <fieldset>
            <legend>Preferred Chat Layout</legend>
            <?php
            $currentLayout = $currentUser->getChatLayout();
            ?>
            <input type="radio" id="oneLine" name="chatLayout" value="oneLine"
                <?= ($currentLayout === 'oneLine') ? 'checked' : '' ?>>
            <label for="oneLine">Username and message in one line</label><br>
            <input type="radio" id="twoLines" name="chatLayout" value="twoLines"
                <?= ($currentLayout === 'twoLines') ? 'checked' : '' ?>>
            <label for="twoLines">Username and message in separate lines</label><br>
        </fieldset>

        <button type="button" onclick="window.location.href='freundeliste.php'">Cancel</button>
        <input type="submit" value="Save">
    </form>
</body>
</html>
