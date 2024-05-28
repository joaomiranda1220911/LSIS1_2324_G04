<?php
include("ImportSQL.php");
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
            <h1>Coleções</h1>
            <form class="search-form" action="#" method="GET">
                <input type="text" name="search" placeholder="Pesquisar...">
                <button type="submit">Buscar</button>
            </form>

            <div class="user-dropdown">
                <a href="#" class="user-button">
                    <img src="https://static.vecteezy.com/system/resources/thumbnails/002/318/271/small_2x/user-profile-icon-free-vector.jpg" alt="Ícone de usuário">
                </a>
                <ul class="dropdown-menu">
                    <li><a href="Registo.php">Registo</a></li>
                    <li><a href="Login.php">Login</a></li>
                    <li><a href="Utilizador.php">Área de utilizador</a></li>
                    <li><a href="Logout.php">Terminar Sessão</a></li>
                </ul>
            </div>
        </header>

        <nav>

        </nav>

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
            <p>Conheça as nossas coleções</p>
            <p>Siga-nos nas redes sociais</p>
        </footer>

    </body>
</html>


