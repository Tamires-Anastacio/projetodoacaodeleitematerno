<?php
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json");

  $dados = [
    ["id" => 1, "nome" => "Maria"],
    ["id" => 2, "nome" => "João"],
  ];

  echo json_encode($dados);
?>