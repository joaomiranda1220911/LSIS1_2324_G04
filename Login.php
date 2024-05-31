<!DOCTYPE html>
<html lang="pt">

<head>
    <title> LSIS1 </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>

    </header>




    <form action="login.php" method="POST" class="login-form-container">
        <div>
            <h2> Login </h2>
        </div>
        <div class="login-form-container">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="login-form-container">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button name="submit" type="submit">Login</button>
    </form>

</body>

</html>