<?php
require("../database/conexao.php");

$sqlRead = " SELECT * FROM tbl_categoria ";

$resultado = mysqli_query($conexao, $sqlRead) or die(mysqli_error($conexao));

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles-global.css" />
    <link rel="stylesheet" href="./categorias.css" />
    <title>Administrar Categorias</title>
</head>

<body>
    <?php
    include("../componentes/header/header.php");

    if (!isset($_SESSION["usuarioId"])) {

        $_SESSION["mensagem"] = "Você precisa fazer login para acessar essa página.";

        header("location: ../produtos/index.php");
    }
    ?>
    <div class="content">
        <section class="categorias-container">
            <main>
                <form class="form-categoria" method="POST" action="./acoesCategorias.php">
                    <input type="hidden" name="acao" value="inserir" />
                    <h1 class="span2">Adicionar Categorias</h1>
                    <ul>
                        <?php
                        if (isset($_SESSION["erros"])) {
                            foreach ($_SESSION["erros"] as $erro) {
                        ?>
                                <li><?= $erro ?></li>
                        <?php
                            }
                            unset($_SESSION["erros"]);
                        }
                        ?>
                    </ul>
                    <div class="input-group span2">
                        <label for="descricao">Descrição</label>
                        <input type="text" name="descricao" id="descricao" requrired />
                    </div>
                    <button type="button" onclick="javascript:window.location.href = '../produtos/index.php'">Cancelar</button>
                    <button>Salvar</button>
                </form>
                <h1>Lista de Categorias</h1>
                <?php
                if (mysqli_num_rows($resultado) == 0) {
                    echo "<p style='text-align: center'>Nenhuma categoria cadastrada.</p>";
                }
                while ($categoria = mysqli_fetch_array($resultado)) {
                ?>
                    <div class="card-categorias">
                        <?= $categoria["descricao"] ?>
                        <img onclick="deletar(<?=$categoria['id'] ?>)"src="https://freesvg.org/img/trash.png" />
                    </div>
                <?php
                }
                ?>
                <form id="deletar" method="POST" action="./acoesCategorias.php">
                    <input type="hidden" name="acao" value="deletar" />
                    <input type="hidden" id="categoriaId" name="categoriaId" value="" />
                </form>
            </main>
        </section>
    </div>
    <script lang="javascript">
        const deletar = (categoriaId) => {
            document.getElementById("categoriaId").value = categoriaId;
            document.getElementById("deletar").submit(); 
        }
    </script>
</body>

</html>