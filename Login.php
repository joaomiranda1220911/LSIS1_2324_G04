<?php
session_start();
include("ImportSQL.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM utilizador WHERE email='" . mysqli_real_escape_string($mysqli, $email) . "'";
    $result = mysqli_query($mysqli, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            // Senha correta, iniciar sessão
            $_SESSION["email"] = $email;
            header("Location: index.php");
            exit;
        } else {
            // Senha incorreta
            echo "<script>alert('Senha incorreta.');</script>";
        }
    } else {
        // Utilizador não encontrado
        echo "<script>alert('Utilizador não encontrado.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Login</title>
    <link rel="icon" href="Imagens/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <div class="logo" onclick="window.location.href='index.php'">
        <img src="Imagens/casa_icon.png" alt="Logo">
    </div>
    <nav>
        <div class="nav-buttons">
            <button><a href="SobreNos.php">Sobre Nós</a></button>
            <button><a href="home_dados.php">Dados</a></button>
            <button><a href="Analise.php">Análise</a></button>
            <button><a href="Mapa.php">Mapa</a></button>
        </div>
    </nav>
    <form action="search.php" method="GET" class="search-bar">
        <input type="text" name="query" placeholder="Pesquisar">
        <button type="submit" class="search-button">
            <img src="Imagens/search_icon.png" alt="ir">
        </button>
    </form>

    <?php
    include("ImportSQL.php");

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $nome_utilizador = "Utilizador";

    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        $sql = "SELECT nome FROM utilizador WHERE email = '$email'";
        $result = mysqli_query($mysqli, $sql);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $nome_utilizador = $row['nome'];
        }
    } else {
        $nome_utilizador = "Visitante";
    }
    ?>

    <div class="dropdown">
        <button class="user-info">
            <img src="Imagens/user_icon.png" alt="User Icon">
            <span><?php echo $nome_utilizador; ?></span>
        </button>
        <div class="dropdown-content">
            <a href="Login.php">Login</a>
            <a href="Register.php">Registo</a>
            <a href="User.php">Perfil</a>
            <a href="Logout.php">Sair</a>
        </div>
    </div>
</header>

<main class="login-container">
    <div class="login-box">
        <form action="Login.php" method="POST">
            <div>
                <h2>Iniciar Sessão</h2>
            </div>
            <input type="email" placeholder="Email" id="email" name="email" required>
            <input type="password" placeholder="Password" id="password" name="password" required>
            <button name="submit" type="submit">Login</button>
        </form>
    </div>
    <div class="separator"></div>
    <div class="login-registar">
        <p>Se ainda não tem conta, crie aqui e comece a tirar partido.</p>
        <button><a href="Register.php">Registar</a></button>
    </div>
</main>

<footer>
    <div class="footer-content">
        <div class="footer-left">
            <img src="Imagens/isep_logo.png" alt="ISEP Logo" class="isep_img" onclick="window.open('https://www.isep.ipp.pt', '_blank');">
            <img src="Imagens/e-redes.jpeg" alt="E-Redes Logo" class="eredes_img" onclick="window.open('https://www.e-redes.pt/pt-pt', '_blank');">
        </div>
        <div class="footer-right">
            <p>Projeto realizado no âmbito de Laboratório de Sistemas 1</p>
        </div>
    </div>
</footer>

<script src="script.js"></script>
</body>
</html>
