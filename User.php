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

    <nav>

    </nav>

    <?php
    session_start();
    include 'db_connection.php'; // Arquivo que contém a conexão com o banco de dados

    if (!isset($_SESSION['email'])) {
        header("Location: login.php");
        exit();
    }

    $email = $_SESSION['email'];
    $sql = "SELECT nome, email, nif FROM utilizador WHERE email = '$email'";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "Erro ao encontrar os dados do utilizador.";
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Processar a submissão do nome
        if (isset($_POST['submitName']) && !empty($_POST['name'])) {
            $newName = $_POST['name'];
            $sql = "UPDATE usuario SET nome = '$newName' WHERE email = '$email'";
            if ($mysqli->query($sql) === TRUE) {
                echo "<script>alert('Nome atualizado com sucesso');</script>";
                $user['nome'] = $newName; // Atualiza o nome na variável $user
            } else {
                echo "Erro ao atualizar o nome: " . $mysqli->error;
            }
        }

        // Processar a submissão do email
        if (isset($_POST['submitEmail']) && !empty($_POST['email'])) {
            $newEmail = $_POST['email'];
            $sql = "UPDATE usuario SET email = '$newEmail' WHERE email = '$email'";
            if ($mysqli->query($sql) === TRUE) {
                echo "<script>alert('Email atualizado com sucesso');</script>";
                $_SESSION['email'] = $newEmail; // Atualiza o email na sessão
                $user['email'] = $newEmail; // Atualiza o email na variável $user
            } else {
                echo "Erro ao atualizar o email: " . $mysqli->error;
            }
        }


        // Processar a submissão da senha
        if (isset($_POST['submitPassword']) && !empty($_POST['password']) && !empty($_POST['confirm_password'])) {
            if ($_POST['password'] === $_POST['confirm_password']) {
                $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $sql = "UPDATE usuario SET senha = '$newPassword' WHERE email = '$email'";
                if ($mysqli->query($sql) === TRUE) {
                    echo "<script>alert('Senha atualizada com sucesso');</script>";
                } else {
                    echo "Erro ao atualizar a senha: " . $mysqli->error;
                }
            } else {
                echo "As senhas não coincidem.";
            }
        }
    }
    ?>

    <div class="form-container">
        <h2>Dados do Usuário</h2>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-row">
                <label for="name">Nome:</label>
                <input type="text" id="name" name="name" placeholder="Nome">
                <span>Atual: <?php echo htmlspecialchars($user['nome']); ?></span>
                <button type="submit" name="submitName">Alterar Nome</button>
            </div>
            <div class="form-row">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Email">
                <span>Atual: <?php echo htmlspecialchars($user['email']); ?></span>
                <button type="submit" name="submitEmail">Alterar Email</button>
            </div>

            <div class="form-row">
                <label for="password">Nova Senha:</label>
                <input type="password" id="password" name="password" placeholder="Nova Senha">
                <button type="submit" name="submitPassword">Alterar Senha</button>
            </div>
            <div class="form-row">
                <label for="confirm_password">Confirmar Nova Senha:</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmar Nova Senha">
            </div>
        </form>
    </div>

</body>

</html>