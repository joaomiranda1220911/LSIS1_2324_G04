<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("ImportSQL.php");

$flag = false;
$flag_email = false;
$flag_pass = false;
$error_message = "";
$success_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $nome = $_POST['name'];
    $email = $_POST['email'];
    $permissao = $_POST['permissao'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!$mysqli) {
        echo "<script>alert('Erro ao ligar a base de dados.')</script>";
    } else {
        // Verificar se o email já existe
        $query = "SELECT email FROM utilizador WHERE email='" . $email . "'";
        $result = mysqli_query($mysqli, $query);
        if ($result) {
            $n = mysqli_num_rows($result);
            if ($n > 0) {
                $flag = true;
                $flag_email = true;
                echo "<script>alert('Este email já se encontra registado.')</script>";
            }
        } else {
            echo "<script>alert('Erro ao executar a consulta: ' '". mysqli_error($mysqli). "')</script>";
        }

        // Validação da password
        if ($password != $confirm_password || $password == "") {
            $flag = true;
            $flag_pass = true;
            echo "<script>alert('As passwords não coincidem ou estão vazias.')</script>";
        }

        // Inserir dados na base de dados se não houver erro
        if ($flag == false) {
            // Hash da senha
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insere = "INSERT INTO utilizador (email, password, nome, permissao) VALUES ('" . $email . "','" . $hashed_password . "','" . $nome . "','" . $permissao . "')";
            $result = mysqli_query($mysqli, $insere);
            if ($result) {
                echo "<script>alert('Conta criada com sucesso!')</script>";
            } else {
                echo "<script>alert('Dados não inseridos: ' '". mysqli_error($mysqli). "')</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <title>Página de Registo</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="Imagens/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles.css">
    <script>
        function togglePasswordVisibility(inputId) {
            var input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    </script>
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
        // Incluir o arquivo de configuração da conexão com o banco de dados
        include("ImportSQL.php");

        // Verificar se a sessão já está ativa
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Definir um nome padrão
        $nome_utilizador = "Utilizador";

        // Verificar se o usuário está logado
        if (isset($_SESSION['email'])) {
            $email = $_SESSION['email'];

            // Query para selecionar o nome do usuário
            $sql = "SELECT nome FROM utilizador WHERE email = '$email'";
            $result = mysqli_query($mysqli, $sql);

            if ($result) {
                $row = mysqli_fetch_assoc($result);
                $nome_utilizador = $row['nome'];
            }
        }else{
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

    <main class="register-container">
        <?php if (!empty($error_message)) : ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)) : ?>
            <div class="success-message">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <form action="Register.php" method="POST" class="register-box" style="border: 1px solid #cccccc; padding: 20px; border-radius: 5px;">
            <div>
                <h2>Criar Conta</h2>
            </div>
            <div class="input-container">
                <input type="text" placeholder="Nome" name="name" required>
            </div>
            <div class="input-container">
                <input type="email" placeholder="Email" id="email" name="email" required>
            </div>
            <div class="input-container">
                <select id="permissao" name="permissao" required>
                    <option value="" disabled selected>Tipo de Utilizador</option>
                    <option value="Utilizador">Utilizador</option>
                    <option value="Admin">Admin</option>
                    <option value="colaborador E-Redes">Colaborador E-Redes</option>
                </select>
            </div>
            <div class="input-container">
                <input type="password" placeholder="Password" id="password" name="password" required>
            </div>
            <div class="input-container">
                <input type="password" placeholder="Confirmar Password" id="confirm_password" name="confirm_password" required>
            </div>
            <button name="submit" type="submit">Registar</button>
        </form>
        <div class="separator"></div>
        <div class="login-registar">
            <p>Se já tem conta, inicie sessão.</p>
            <button><a href="Login.php">Login</a></button>
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
