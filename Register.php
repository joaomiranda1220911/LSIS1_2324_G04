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
        $error_message = "Erro ao ligar à base de dados";
    } else {
        // Verificar se o email já existe
        $query = "SELECT email FROM utilizador WHERE email='" . $email . "'";
        $result = mysqli_query($mysqli, $query);
        if ($result) {
            $n = mysqli_num_rows($result);
            if ($n > 0) {
                $flag = true;
                $flag_email = true;
                $error_message = "Email já existente";
            }
        } else {
            $error_message = "Erro ao executar a consulta: " . mysqli_error($mysqli);
        }

        // Validação da password
        if ($password != $confirm_password || $password == "") {
            $flag = true;
            $flag_pass = true;
            $error_message = "As passwords não coincidem ou estão vazias";
        }

        // Inserir dados na base de dados se não houver erro
        if ($flag == false) {
            $pass = md5($password);
            $insere = "INSERT INTO utilizador (email, nome, permissao, password) VALUES ('" . $email . "','" . $nome . "','" . $password . "','" . $permissao . "')";
            $result = mysqli_query($mysqli, $insere);
            if ($result) {
                $success_message = "Dados inseridos com sucesso";
            } else {
                $error_message = "Dados não inseridos: " . mysqli_error($mysqli);
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
                <button><a href="Dados.php">Dados</a></button>
                <button><a href="Analise.php">Análise</a></button>
            </div>
        </nav>
        <div class="search-bar">
            <input type="text" placeholder="Pesquisar">
            <button class="search-button"><img src="Imagens/search_icon.png" alt="ir"></button>
        </div>
        <div class="dropdown">
            <div class="user-info">
                <img src="Imagens/user_icon.png" alt="User Icon">
                <span>Name</span>
                <div class="dropdown-content">
                    <a href="Login.php">Login</a>
                    <a href="Register.php">Registo</a>
                    <a href="User.php">Perfil</a>
                    <a href="#">Sair</a>
                </div>
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

        <form action="Registo.php" method="POST" class="register-box" style="border: 1px solid #cccccc; padding: 20px; border-radius: 5px;">
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
                <button type="button" onclick="togglePasswordVisibility('password')">
                    Mostrar
                </button>
            </div>
            <div class="input-container">
                <input type="password" placeholder="Confirmar Password" id="confirm_password" name="confirm_password" required>
                <button type="button" onclick="togglePasswordVisibility('confirm_password')">
                    Mostrar
                </button>
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