<?php
//Sessão é um vínculo entre o navegador do usuário e o servidor back-end


session_start();

require("../database/conexao.php");

function validarCampos()
{
    $erros = [];

    if (!isset($_POST["descricao"]) && $_POST["descricao"] == "") {
        $erros[] = "O campo descrição é obrigatório";
    }
    
    if (!isset($_POST["peso"]) && $_POST["peso"] == "") {
        $erros[] = "O campo peso é obrigatório";
    } elseif (!is_numeric(str_replace(",", ".", $_POST["peso"]))) {
        $erros[] = "O campo peso deve ser um número";
    }

    if (!isset($_POST["quantidade"]) && $_POST["quantidade"] == "") {
        $erros[] = "O campo quantidade é obrigatório";
    } elseif (!is_numeric(str_replace(",", ".", $_POST["quantidade"]))) {
        $erros[] = "O campo quantidade deve ser um número";
    }

    if (!isset($_POST["cor"]) && $_POST["cor"] == "") {
        $erros[] = "O campo cor é obrigatório";
    }

    if (!isset($_POST["valor"]) && $_POST["valor"] == "") {
        $erros[] = "O campo valor é obrigatório";
    } elseif (!is_numeric(str_replace(",", ".", $_POST["valor"]))) {
        $erros[] = "O campo valor deve ser um número";
    }

    if (!isset($_POST["desconto"]) && $_POST["desconto"] == "") {
        $erros[] = "O campo desconto é obrigatório";
    } elseif (!is_numeric(str_replace(",", ".", $_POST["desconto"]))) {
        $erros[] = "O campo desconto deve ser um número";
    }

    return $erros;
}

switch ($_POST["acao"]) {
    case "inserir":
        $erros = validarCampos();

        if (count($erros) > 0) {
            $_SESSION["erros"] = $erros;


            header("location: novo/index.php");
        }

        $descricao = $_POST["descricao"];
        $peso = $_POST["peso"];
        $quantidade = $_POST["quantidade"];
        $cor = $_POST["cor"];
        $tamanho = $_POST["tamanho"];
        $valor = $_POST["valor"];
        $desconto = $_POST["desconto"] != "" ? $_POST["desconto"] : 0;

        $sqlInsert = " INSERT INTO tbl_produto (descricao, peso, quantidade, cor, tamanho, valor, desconto) 
                       VALUES ('$descricao', '$peso', $quantidade, '$cor', '$tamanho', '$valor', $desconto) ";

        $resultado = mysqli_query($conexao, $sqlInsert) or die(mysqli_error($conexao));

        if ($resultado) {
            $mensagem = "Produto Inserido Com Sucesso!";
        } else {
            $mensagem = "Erro ao inserir o produto!";
        }

        header("location: index.php");
        
        break;
}
