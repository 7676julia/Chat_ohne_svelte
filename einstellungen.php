<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="app.css">
</head>

<body>
    <h1 class="left">Profile Settings</h1>
    <form action="einstellungen.php" method="get"></form>>
    <fieldset>
        <legend>Base Data</legend>
        <label for="FirstName">First Name</label>
        <input type="text" id="usernameFeld" name="username" placeholder="Your Name"><br>

        <label for="LastName">Last Name</label>
        <input type="text" id="passwordFeld" name="password" placeholder="Your Surname"><br>

        <label for="CoffeeOrTea">Coffee or Tea?</label>
        <select id="bavarages" name="bavarages">
            <option value="Coffee">Neither nor</option>
            <option value="Coffee">Coffee</option>
            <option value="Coffee">Tea</option>
            <option value="Coffee">Both</option>
        </select>
    </fieldset>

    <fieldset>
        <legend>Tell Soemthing About You</legend>
        <textarea rows='6' cols='110'></textarea>

    </fieldset>

    <fieldset>
        <legend>Prefered Chat Layout</legend>
        <input type='radio' id='oneLine' name='chatLayout' value='prefered'>
        <label for='oneLine'>Username and message in one line</label><br>
        <input type='radio' id='twoLines' name='chatLayout' value='prefered'>
        <label for='twoLines'>Username and message in separate lines</label><br>
    </fieldset>
    <button type="button" onclick="window.location.href='freundeliste.php'">Cancel</button>
    <input type="submit" value="Save">
    </form>
</body>

</html>