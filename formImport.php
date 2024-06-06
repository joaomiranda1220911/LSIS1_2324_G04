<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
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
        <div class="search-bar">
            <input type="text" placeholder="Pesquisar">
            <button class="search-button"><img src="Imagens/search_icon.png" alt="ir"></button>
        </div>
        <div class="dropdown">
            <button class="user-info">
                <img src="Imagens/user_icon.png" alt="User Icon">
                <span>Name</span>
            </button>
            <div class="dropdown-content">
                <a href="Login.php">Login</a>
                <a href="Register.php">Registo</a>
                <a href="User.php">Perfil</a>
                <a href="Logout.php">Sair</a>
            </div>
        </div>
    </header>

    <div class="form-container">
        <h2>Importar Dados</h2>

        <form method="POST" enctype="multipart/form-data">

            <label for="nome_tabela">Nome da Tabela:</label>
            <input type="text" id="nome_tabela" name="nome_tabela" required>

            <label for="tag_tabela">Tag da Tabela:</label>
            <select id="tag_tabela" name="tag_tabela" required>
                <option value="">Selecione uma tag</option>
                <option value="tag1">Operação e Qualidade de Serviço</option>
                <option value="tag2">Rede Elétrica</option>
                <option value="tag3">Consumos e Energia</option>
                <option value="tag4">Mobilidade Elétrica</option>
                <option value="tag5">Renováveis</option>
            </select>

            <div id="uploadFieldContainer"></div>

            <label for="informacao_tabela">Informação da Tabela:</label>
            <textarea id="informacao_tabela" name="informacao_tabela" rows="4" cols="50" required></textarea>

            <label for="numero_linhas">Número de Linhas:</label>
            <input type="number" id="numero_linhas" name="numero_linhas" required>

            <label for="upload_ficheiro">Upload de Ficheiro:</label>
            <input type="file" id="fileUpload" name="fileUpload" class="file-upload" accept=".csv, .xlsx, .xls" required>


            <input type="submit" value="Importar Dados" class="submit-button">
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
</body>

</html>