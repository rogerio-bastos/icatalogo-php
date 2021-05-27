<?php
session_start();

require("../database/conexao.php");

function validaCampos()
{
    $erros = [];

    if (!isset($_POST["descricao"]) || $_POST["descricao"] == "") {
        $erros[] = "O campo descrição é obrigatório.";
    }

    return $erros;
}

switch ($_POST["acao"]) {
    case "inserir":

        $erros = validaCampos();

        if (count($erros) > 0) {
            $_SESSION["erros"] = $erros;

            header("location: ./index.php");

            exit();  
        }

        $descricao = $_POST["descricao"];

        $sqlInsert = " INSERT INTO tbl_categoria(descricao) VALUES('$descricao') ";

        $resultado = mysqli_query($conexao, $sqlInsert) or die(mysqli_error($conexao));

        if ($resultado) {
            $_SESSION["mensagem"] = "Categoria inserida com sucesso!";
        } else {
            $_SESSION["mensagem"] = "Ops, houve algum erro";
        }

        header("location: ./index.php");

        break;

    case "deletar":
        if (isset($_POST["categoriaId"])) {
            $categoriaId = $_POST["categoriaId"];

            $sqlDelete = " DELETE FROM tbl_categoria WHERE id = $categoriaId ";

            $resultado = mysqli_query($conexao, $sqlDelete) or die(mysqli_error($conexao));;
            
            if ($resultado) {
                $_SESSION["mensagem"] = "Categoria excluída com sucesso!";
            } else {
                $_SESSION["mensagem"] = "Ops, erro ao excluir";
            }

            header("location: ./index.php");
        }
        break;
}
