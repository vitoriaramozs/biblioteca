<?php

require "config.php";

$rota = $_GET["rota"] ?? ($_SERVER["REQUEST_METHOD"] === "POST" ? "livros" : "teste");

function teste() {
    echo "API respondendo dom sucesso!";
}

function listarLivros($con){
    header("Content-Type: application/json; charset=utf-8");
    $stmt = $con->query("SELECT * FROM livros");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

function filtrarAutor($con){
    header("Content-Type: application/json; charset=utf-8");
    $nome = $_GET["nome"] ?? "";
    $stmt = $con->prepare("SELECT * FROM livros WHERE autorLivro = ?");
    $stmt->execute([$nome]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

function anoPublicacao($con){
    header("Content-Type: application/json; charset=utf-8");
    $ano = $_GET["ano"] ?? "";
    $stmt = $con->prepare("SELECT * FROM livros WHERE ano_publicacao = ?");
    $stmt->execute([$ano]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

function adicionarLivros($con){
    $autorLivro = $_POST["autorLivro"] ?? "";
    $descricaoLivro = $_POST["descricaoLivro"] ?? "";
    $ano_publicacao = $_POST["ano_publicacao"] ?? "";

    try {
        $stmt = $con->prepare("INSERT INTO livros (autorLivro, descricaoLivro, ano_publicacao) VALUES (?,?,?)");
        $stmt -> execute([$autorLivro, $descricaoLivro, $ano_publicacao]);
        header("Location: ../front/index.html");
    }catch(PDOException $e){
        header("Location: ../front/erro.html");
    }
    exit;
}

if($_SERVER["REQUEST_METHOD"] === "POST"){
    adicionarLivros($con);
}elseif ($rota === "listar/livros"){
    listarLivros ($con);
}elseif ($rota === "filtrar/autor"){
    filtrarAutor($con);
}else {
    teste();
}
?>