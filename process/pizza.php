<?php

    include_once("conn.php");

    $method = $_SERVER["REQUEST_METHOD"];

    // Resgatando os dados, montagem de pedidos
    if($method === "GET") {

        $bordasQuery = $conn->query("SELECT * FROM bordas;");

        $bordas = $bordasQuery->fetchAll();

        $massasQuery = $conn->query("SELECT * FROM massas;");

        $massas = $massasQuery->fetchAll();

        $saboresQuery = $conn->query("SELECT * FROM sabores;");

        $sabores = $saboresQuery->fetchAll();

    // Enviando dados, criação de pedidos
    } else if($method === "POST") {

    }

?>