<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="app.css">
    <script src="frendesliste.js"></script>
</head>
<body>
    <h1>Friends</h1>
    <a href="logout.html" Logout> &lt; Logout</a> | <a href="einstellungen.html">Settings</a>
    <hr>
    <ul id = "friends-list">
        
    </ul>

    <hr>
    <h2>New Requests</h2>
    <ul id = "friend-requests">
        

    </ul>
    <button>Accept</button> <button>Reject</button>
    <hr>

    <form action="freundeliste.html" method="get">
        <label for="eintragFeld"></label>
        <input type="text" id="eintragFeld" name="eintrag" placeholder="Add friends to List" list="friend-selector">
        <datalist id="friend-selector">
        </datalist>
        <button type="button">Add</button>
        <input type="submit" value="Add">
    </form>
  
</body>
</html>
