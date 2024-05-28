<?php
session_start();
include("ImportSQL.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $query = "SELECT * FROM utilizador WHERE email='" . $email . "'";
    $result = mysqli_query($mysqli, $query);
    $user = mysqli_fetch_assoc($result);

    $_SESSION["email"] = $email;
    $_SESSION["password"] = $password;

    if ($user) {
        if ($password == $user['password']) {
            header("Location: index.php");
            exit;
        } else {
            echo "<script>alert('Senha incorreta');</script>";
        }
    } else {
        echo "<script>alert('Usuário não encontrado');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
    <head>
        <title>Coleções</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <header>

        </header>

        <nav>

</nav>


        <form action="login.php" method="POST" class="login-form-container">
            <div> <h2> Login </h2> </div>
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

        <footer>
            <p>Conheça as nossas coleções</p>
            <p>Siga-nos nas redes sociais</p>
        </footer>
    </body>
</html>
