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
    <title>Profile Settings - Chat Application</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-4">
        <h1 class="mb-4">Profile Settings</h1>

        <form action="einstellungen.php" method="post">
            <!-- Base Data Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Base Data</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" 
                               placeholder="Your Name" value="<?= htmlspecialchars($currentUser->getFirstName() ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" 
                               placeholder="Your Surname" value="<?= htmlspecialchars($currentUser->getLastName() ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="beverages" class="form-label">Coffee or Tea?</label>
                        <select class="form-select" id="beverages" name="beverages">
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
                </div>
            </div>

            <!-- About Me Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tell Something About You</h5>
                </div>
                <div class="card-body">
                    <textarea class="form-control" name="description" rows="6" 
                              placeholder="Write something about yourself..."><?= htmlspecialchars($currentUser->getDescription() ?? '') ?></textarea>
                </div>
            </div>

            <!-- Chat Layout Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Preferred Chat Layout</h5>
                </div>
                <div class="card-body">
                    <?php $currentLayout = $currentUser->getChatLayout(); ?>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" id="oneLine" name="chatLayout" 
                               value="oneLine" <?= ($currentLayout === 'oneLine') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="oneLine">
                            Username and message in one line
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="twoLines" name="chatLayout" 
                               value="twoLines" <?= ($currentLayout === 'twoLines') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="twoLines">
                            Username and message in separate lines
                        </label>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-2 justify-content-end">
                <a href="freundeliste.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>