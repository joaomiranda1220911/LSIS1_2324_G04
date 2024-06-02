<?php
include("ImportSQL.php");
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <title>Coleções</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <div class="logo">
            <a href="index.php">
                <img src="Imagens/casa_icon.png" alt="Logo">
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="#">Sobre Nós</a></li>
                <li><a href="Dados.php">Dados</a></li>
                <li><a href="#">Análise</a></li>
            </ul>
        </nav>
        <div class="search-bar">
            <button class="eredes_btn" onclick="window.open('https://www.e-redes.pt/pt-pt', '_blank');">Site oficial E-redes</button>
            <input type="text" placeholder="Pesquisar">
            <button class="search-button">Ir</button>
        </div>
        <div class="user-info">
            <img src="Imagens/user_icon.png" alt="User Icon">
            <span>Name</span>
        </div>
    </header>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Processar a submissão do nome
        if (isset($_POST['submitName']) && !empty($_POST['name'])) {
            $newName = $_POST['name'];
            $email = $_SESSION['email'];
            $sql = "UPDATE usuario SET nome = '$newName' WHERE email = '$email'";
            if ($mysqli->query($sql) === TRUE) {
                echo "<script>alert('Nome atualizado com sucesso');</script>";
            } else {
                echo "Erro ao atualizar o nome: " . $mysqli->error;
            }
        }

        // Processar a submissão do email
        if (isset($_POST['submitEmail']) && !empty($_POST['email'])) {
            // Lógica para atualizar o email na base de dados
        }

        // Processar a submissão do NIF
        if (isset($_POST['submitNIF']) && !empty($_POST['nif'])) {
            // Lógica para atualizar o NIF na base de dados
        }

        // Processar a submissão da senha
        if (isset($_POST['submitPassword']) && !empty($_POST['password']) && !empty($_POST['confirm_password'])) {
            // Lógica para atualizar a senha na base de dados
        }
    }
    ?>

    <div class="form-container">
        <h2>Dados do Usuário</h2>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-container">
                <input type="text" id="name" name="name" placeholder="Nome">
                <button type="submit" name="submitName">Alterar Nome</button>
            </div>
            <div class="form-container">
                <input type="email" id="email" name="email" placeholder="Email">
                <button type="submit" name="submitEmail">Alterar Email</button>
            </div>
            <div class="form-container">
                <input type="text" id="nif" name="nif" placeholder="NIF">
                <button type="submit" name="submitNIF">Alterar NIF</button>
            </div>
            <div class="form-container">
                <input type="password" id="password" name="password" placeholder="Nova Password">
                <button type="submit" name="submitPassword">Alterar Password</button>
            </div>
            <div class="form-container">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmar Nova Password">
            </div>
        </form>
    </div>



    <footer>
        <div class="footer-content">
            <img src="Imagens/isep_logo.png" alt="ISEP Logo">
            <p>Projeto realizado no âmbito de Laboratório de Sistemas 1</p>
        </div>
    </footer>

</body>

</html>