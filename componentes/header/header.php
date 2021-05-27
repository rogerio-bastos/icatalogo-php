<?php
session_start();
?>

<link rel="stylesheet" href="/web-backend/icatalogo-parte1/componentes/header/header.css">
<div class="mensagens">
    <?php
    if (isset($_SESSION["erros"])) {
        echo $_SESSION["erros"][0];
    }

    if (isset($_SESSION["mensagem"])) {
        echo $_SESSION["mensagem"];
    }

    unset($_SESSION["erros"]);
    unset($_SESSION["mensagem"]);
    ?>
</div>
<header class="header">
    <figure>
        <img src="/web-backend/icatalogo-parte1/imgs/logo.png" alt="logo">
    </figure>
    <input type="search" placeholder="Pesquisar" />
    <?php
    if (!isset($_SESSION["usuarioId"])) {
    ?>
        <nav>
            <ul>
                <a id="menu-admin">Administrar</a>
            </ul>
        </nav>
        <div id="container-login" class="container-login">
            <h1>Fazer Login</h1>
            <form method="POST" action="/web-backend/icatalogo-parte1/componentes/header/acoesLogin.php">
                <input type="hidden" name="acao" value="login" />
                <input type="text" name="usuario" placeholder="Usuário" />
                <input type="password" name="senha" placeholder="Senha" />
                <button>Entrar</button>
            </form>
        </div>
    <?php
    } else {
    ?>
        <nav>
            <ul>
                <a id="menu-admin" onclick="logout();">Sair</a>
            </ul>
        </nav>
        <form id="form-logout" style="display:none" method="POST" action="/web-backend/icatalogo-parte1/componentes/header/acoesLogin.php">
            <input type="hidden" name="acao" value="logout" />
        </form>
    <?php
    }
    ?>
</header>

<script lang="javascript">
    document.querySelector("#menu-admin").addEventListener("click", toggleLogin);

    const logout = () => document.getElementById("form-logout").submit();

    function toggleLogin() {
        let containerLogin = document.querySelector("#container-login");
        //se estiver oculto, mostra 
        if (containerLogin.style.opacity == 0) {
            containerLogin.style.opacity = 1;
            containerLogin.style.height = "200px";
            //se não, oculta
        } else {
            containerLogin.style.opacity = 0;
            containerLogin.style.height = "0px";
        }
    }
</script>