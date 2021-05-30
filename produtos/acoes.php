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

    //Verificar se o campo foto está vindo e se ele é uma imagem
    if($_FILES["foto"]["error"] == UPLOAD_ERR_NO_FILE){
        $erros[] = "Você precisa enviar uma imagem";
    }else{
        //se o arquivo é uma imagem
        $imagemInfo = getimagesize($_FILES["foto"]["tmp_name"]);
        //Se não for uma imagem 
        if(!$imagemInfo){
            $erros[] = "O arquivo precisa ser uma imagem";
        }
        //Se a imagem for maior que 2MB
        if($_FILES["foto"]["size"] > 1024 * 1024 * 2){
            $erros[] = "O arquivo não pode ser maior que 2MB";
        }
        //Se a imagem não for quadrada 
        if($imagemInfo[0] != $imagemInfo[1]){
            $erros[] = "A imagem deve ser quadrada";
        }
    }

    if(!isset($_POST["categoria"]) || $_POST["categoria"] == ""){
        $erros[] = "Uma categoria deve ser selecionada";
    }

    return $erros;
}

switch ($_POST["acao"]) {
    case "inserir":
        $erros = validarCampos();

        if (count($erros) > 0) {
            $_SESSION["erros"] = $erros;


            header("location: novo/index.php");

            exit();
        }
        //Nome original do arquivo
        $nomeArquivo = $_FILES["foto"]["name"];
        //Extensao do arquivo
        $extensao = pathinfo($nomeArquivo, PATHINFO_EXTENSION);
        //Novo nome único utilizando o unix timestamp
        $novoNomeArquivo = md5(microtime()) . ".$extensao";
        //movemos a foto para a pasta fotos dentro de produtos
        move_uploaded_file($_FILES["foto"]["tmp_name"], "fotos/$novoNomeArquivo");

        $descricao = $_POST["descricao"];
        $peso = str_replace(",", ".", $_POST["peso"]);
        $quantidade = $_POST["quantidade"];
        $cor = $_POST["cor"];
        $tamanho = $_POST["tamanho"];
        $valor = str_replace(",", ".", $_POST["valor"]);;
        $desconto = $_POST["desconto"] != "" ? $_POST["desconto"] : 0;
        //Receber o id da categoria
        $categoriaId = $_POST["categoria"];

        //Salvar o id da categoria no produto

        $sqlInsert = " INSERT INTO tbl_produto (descricao, peso, quantidade, cor, tamanho, valor, desconto, imagem, categoria_id) 
                       VALUES ('$descricao', '$peso', $quantidade, '$cor', '$tamanho', '$valor', $desconto, '$novoNomeArquivo', $categoriaId) ";

        $resultado = mysqli_query($conexao, $sqlInsert) or die(mysqli_error($conexao));

        if ($resultado) {
            $mensagem = "Produto Inserido Com Sucesso!";
        } else {
            $mensagem = "Erro ao inserir o produto!";
        }

        $_SESSION["mensagem"] = $mensagem;

        header("location: index.php");
        
        break;
    
    case "deletar":
        $produtoId = $_POST["produtoId"];
        $produtoImagem = $_POST["produtoImagem"];

        $sqlDelete = " DELETE FROM tbl_produto WHERE id = $produtoId ";

        $resultado = mysqli_query($conexao, $sqlDelete);
        
        unlink("./fotos/$produtoImagem");
        
        if ($resultado) {
            $mensagem = "Produto apagado com Sucesso!";
        } else {
            $mensagem = "Erro ao apagar o produto!";
        }

        $_SESSION["mensagem"] = $mensagem;

        header("location: index.php");
        break;

}
