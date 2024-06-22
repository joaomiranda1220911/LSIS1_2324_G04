<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Utilizador</title>
    <link rel="icon" href="Imagens/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles.css">
    <script>
        function eliminarUtilizador(userEmail) {
            if (confirm("Tens a certeza que queres eliminar o utilizador? Esta ação é irreversível.")) {
                document.getElementById('deleteEmail').value = userEmail;
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
    <style>
        .form-container {
            margin-top: 60px;
        }
    </style>
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

    <nav></nav>

    <?php
    if (!isset($_SESSION['email'])) {
        header("Location: login.php");
        exit();
    }

    $email = $_SESSION['email'];
    $sql = "SELECT nome, email FROM utilizador WHERE email = '$email'";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "<script>alert('Erro ao encontrar os dados do utilizador.')</script>";
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['deleteEmail'])) {
            $deleteEmail = $_POST['deleteEmail'];
            $stmt = $mysqli->prepare("DELETE FROM utilizador WHERE email = ?");
            $stmt->bind_param("s", $deleteEmail);

            if ($stmt->execute()) {
                echo "<script>alert('Utilizador eliminado com sucesso.'); window.location.href='Logout.php';</script>";
                exit();
            } else {
                echo "<script>alert('Erro ao eliminar o utilizador: " . mysqli_error($mysqli) . "')</script>";
            }
        } else {
            if (isset($_POST['submitName']) && !empty($_POST['name'])) {
                $newName = $_POST['name'];
                $sql = "UPDATE utilizador SET nome = '$newName' WHERE email = '$email'";
                if ($mysqli->query($sql) === TRUE) {
                    echo "<script>alert('Nome atualizado com sucesso');</script>";
                    $user['nome'] = $newName;
                } else {
                    echo "<script>alert('Erro ao atualizar o nome: " . mysqli_error($mysqli) . "')</script>";
                }
            }

            if (isset($_POST['submitEmail']) && !empty($_POST['email'])) {
                $newEmail = $_POST['email'];
                $sql = "UPDATE utilizador SET email = '$newEmail' WHERE email = '$email'";
                if ($mysqli->query($sql) === TRUE) {
                    echo "<script>alert('Email atualizado com sucesso');</script>";
                    $_SESSION['email'] = $newEmail;
                    $user['email'] = $newEmail;
                } else {
                    echo "<script>alert('Erro ao atualizar o email: " . mysqli_error($mysqli) . "')</script>";
                }
            }

            if (isset($_POST['submitPassword'])) {
                if (!empty($_POST['password']) && !empty($_POST['confirm_password'])) {
                    if ($_POST['password'] === $_POST['confirm_password']) {
                        $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        $sql = "UPDATE utilizador SET password = '$newPassword' WHERE email = '$email'";
                        if ($mysqli->query($sql) === TRUE) {
                            echo "<script>alert('Senha atualizada com sucesso');</script>";
                        } else {
                            echo "<script>alert('Erro ao atualizar a password: " . mysqli_error($mysqli) . "')</script>";
                        }
                    } else {
                        echo "<script>alert('As passwords não coincidem')</script>";
                    }
                } else {
                    echo "<script>alert('Por favor, preencha os dois campos de senha.')</script>";
                }
            }
        }
    }
    ?>

    <div class="form-container">
        <h2>Dados do Utilizador</h2>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-row">
                <label for="name">Nome:</label>
                <input type="text" id="name" name="name" placeholder="Atual: <?php echo htmlspecialchars($user['nome']); ?>">
                <div class="atual_alterar">
                    <button type="submit" name="submitName" class="button">Alterar Nome</button>
                </div>
            </div>
            <div class="form-row">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Atual: <?php echo htmlspecialchars($user['email']); ?>">
                <div class="atual_alterar">
                    <button type="submit" name="submitEmail" class="button">Alterar Email</button>
                </div>
            </div>

            <div class="form-row">
                <label for="password">Nova Password:</label>
                <input type="password" id="password" name="password" placeholder="Nova Password">
            </div>
            <div class="form-row">
                <label for="confirm_password">Confirmar Nova Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmar a Nova Password">
                <button type="submit" name="submitPassword" class="button">Alterar Password</button>
            </div>
        </form>
        <button onclick="eliminarUtilizador('<?php echo $user['email']; ?>')" class="button button-delete">Eliminar Utilizador</button>

        <form id="deleteForm" method="POST" style="display: none;">
            <input type="hidden" id="deleteEmail" name="deleteEmail">
        </form>
    </div>

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
