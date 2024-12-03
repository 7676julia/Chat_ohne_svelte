<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="app.css">
</head>

<body>
    <img src="images/chat.png" style="height: 100px">
    <h1>Please sign in</h1>
    <form action="freundeliste.html" method="get">
        <fieldset>
            <legend>Login</legend>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Username"><br>
            <label for="password">Password</label>
            <input type="text" id="password" name="password" placeholder="Password"><br>
        </fieldset>
        <br>
        <div class="button-container">
        <button type="button" onclick="window.location.href='registrieren.html'">Register</button>
        <input type="submit" value="Login">
        </div>
    </form>
</body>

</html>