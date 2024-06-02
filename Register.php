<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Registo</title>
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
            <button class="search-button">Ir</button>
        </div>
        <div class="dropdown">
            <div class="user-info">
                <img src="Imagens/user_icon.png" alt="User Icon">
                <span>Name</span>
                <div class="dropdown-content">
                    <a href="Login.php">Login</a>
                    <a href="Register.php">Registo</a>
                    <a href="#">Perfil</a>
                    <a href="#">Sair</a>
                </div>
            </div>
        </div>
    </header>

    <main class="register-container">
        <form action="Registo.php" method="POST" class="register-box">
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
                <input type="text" placeholder="NIF" id="nif" name="nif" required>
            </div>
            <div class="input-container">
                <input type="password" placeholder="Password" id="password" name="password" required>
                <button type="button" onclick="togglePasswordVisibility('password')">Mostrar</button>
            </div>
            <div class="input-container">
                <input type="password" placeholder="Confirmar Password" id="confirm_password" name="confirm_password" required>
                <button type="button" onclick="togglePasswordVisibility('confirm_password')">Mostrar</button>
            </div>
            <button name="submit" type="submit">Registar</button>
        </form>
        </div>
        <div class="separator"></div>
        <div class="login-registar">
            <p>Se já tem conta, inicie sessão.</p>
            <button><a href="Login.php">Login</a></button>
        </div>
    </main>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-left">
                <img src="Imagens/isep_logo.png" alt="ISEP Logo" class="isep_img" onclick="window.open('https://www.isep.ipp.pt', '_blank');">
                <img src="Imagens/e-redes.jpg" alt="E-Redes Logo" class="eredes_img" onclick="window.open('https://www.e-redes.pt/pt-pt', '_blank');">
            </div>
            <div class="footer-right">
                <p>Projeto realizado no âmbito de Laboratório de Sistemas 1</p>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>

</html>