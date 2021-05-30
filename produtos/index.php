<?php
require("../database/conexao.php");

if (isset($_GET["search"]) && $_GET["search"] != "") {
    $text = $_GET["search"];

    $sqlRead = " SELECT p.*, c.descricao as categoria from tbl_produto p 
            INNER JOIN tbl_categoria c ON p.categoria_id = c.id 
            WHERE p.descricao LIKE '%$text%'
            OR c.descricao LIKE '%$text%' 
            ORDER BY p.id DESC ";
} else {
    $sqlRead = " SELECT p.*, c.descricao AS categoria FROM tbl_produto p 
        INNER JOIN tbl_categoria c ON p.categoria_id = c.id
        ORDER BY p.id DESC ";
}

$resultado = mysqli_query($conexao, $sqlRead);

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles-global.css" />
    <link rel="stylesheet" href="./produtos.css" />
    <title>Administrar Produtos</title>
</head>

<body>
    <?php
    include("../componentes/header/header.php");
    ?>
    <div class="content">
        <section class="produtos-container">
            <?php
            //Autorização

            //se o usuário estiver logado, mostrar os botões
            if (isset($_SESSION["usuarioId"])) {
            ?>
                <header>
                    <button onclick="javascript:window.location.href ='./novo/'">Novo Produto</button>
                    <button onclick="javascript:window.location.href ='../categorias'">Adicionar Categoria</button>
                </header>
            <?php
            }
            ?>
            <main>
                <?php
                while ($produtos = mysqli_fetch_array($resultado)) {
                    if ($produtos["desconto"] > 0) {
                        $valorProdutoComDesconto = $produtos["valor"] - ($produtos["valor"] * ($produtos["desconto"] / 100));
                        $produtos["valor"] = $valorProdutoComDesconto;
                    }

                    $qtdParcelas = $produtos["valor"] > 1000 ? 12 : 6;

                    $valorParcela = $produtos["valor"] / $qtdParcelas;
                ?>
                    <article class="card-produto">
                        <figure>
                            <img src="/web-backend/icatalogo-parte1/produtos/fotos/<?= $produtos["imagem"] ?>" />
                        </figure>
                        <section>
                            <span class="preco">R$<?= number_format($produtos["valor"], 2, ",", ".") ?></span>
                            <span class="parcelamento">ou em <em><?= $qtdParcelas ?>x R$<?= number_format($valorParcela, 2, ",", ".") ?> sem juros</em></span>

                            <span class="descricao"><?= $produtos["descricao"] ?></span>
                            <span class="categoria">
                                <em><?= $produtos["categoria"] ?></em>
                            </span>
                            <?php
                            if (isset($_SESSION["usuarioId"])) {
                            ?>
                                <form method="POST" action="./acoes.php">
                                    <input type="hidden" name="produtoId" value="<?= $produtos['id']?>"/>
                                    <input type="hidden" name="produtoImagem" value="<?= $produtos['imagem']?>"/>
                                    <input type="hidden" name="acao" value="deletar"/>
                                    <button>X</button>
                                </form>
                            <?php
                            }
                            ?>
                        </section>
                        <footer>

                        </footer>
                    </article>
                <?php
                }
                ?>
            </main>
        </section>
    </div>
    <footer>
        SENAI 2021 - Todos os direitos reservados
    </footer>
</body>

</html>