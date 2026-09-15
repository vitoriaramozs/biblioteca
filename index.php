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

function adicionarLivros($con){
    $autorLivro = $_POST["autorLivro"] ?? "";
    $descricaoLivro = $_POST["descricaoLivro"] ?? "";

    try {
        $stmt = $con->prepare("INSERT INTO livros (autorLivro, descricaoLivro) VALUES (?,?)");
        $stmt -> execute([$autorLivro, $descricaoLivro]);
        header("Location: ../front/index.html");
    }catch(PDOException $e){
        header("Location: ../front/erro.html");
    }
    exit;
}

if($_SERVER["REQUEST_METHOD"] === "POST"){
    adicionarLivros($con);
}elseif ($rota === "livros"){
    listarLivros ($con);
}else {
    teste();
}
?>