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
                    <li><a href="registo.php">Registo</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="Utilizador.php">Área de utilizador</a></li>
                    <li><a href="logout.php">Terminar Sessão</a></li>
                </ul>
            </div>

        </header>

        <nav>
            
        </nav>

        <?php
        if (isset($_POST['submit'])) {

            $flag = false;
            $flag_email = false;
            $flag_pass = false;

            $name = $_POST['name'];
            $email = $_POST['email'];
            $nif = $_POST['nif'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            //Verificar se o email já existe
            $query = "select email from usuario where email='" . $email . "'";
            $result = mysqli_query($mysqli, $query);
            $n = mysqli_num_rows($result);
            if ($n > 0) {
                $flag = true;
                $flag_email = true;
            }

            //Validação da password
            if ($password != $confirm_password || $password == "") {
                $flag = true;
                $flag_pass = true;
            }

            //Existiu um erro
            if ($flag == true) {
                if ($flag_email == true) {
                    ?>
                    <script>
                        alert("Email já existente");
                    </script>
                    <?php
                }
                if ($flag_pass == true) {
                    ?>
                    <script>
                    </script>
                    <?php
                }
            } else {
                $pass = md5($password);
                $insere = "INSERT INTO usuario (email,nome,nif,password) VALUES ('" . $email . "','" . $name . "','" . $nif . "','" . $pass . "')";

                $result = mysqli_query($mysqli, $insere);
                if ($result == 1) {
                    ?>
                    <script>
                        alert("Dados inseridos com sucesso");

                    </script>

                <?php } else {
                    ?>
                    <script>
                        alert("Dados não inseridos");
                    </script>
                    <?php
                }
            }
        } else {
            ?>

            <form action="Registo.php" method="POST" class="register-form-container">
                <div> <h2> Registo </h2> </div>
                <div class="register-form-container">
                    <label for="name">Nome:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="register-form-container">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="register-form-container">
                    <label for="nif">NIF:</label>
                    <input type="text" id="nif" name="nif" required>
                </div>
                <div class="register-form-container">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="register-form-container">
                    <label for="confirm_password">Confirmar Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button name="submit" type="submit">Registar</button>
            </form>

            <?php
        }
        ?>


        <footer>
            <p>Conheça as nossas coleções</p>
            <p>Siga-nos nas redes sociais</p>
        </footer>
    </body>
</html>