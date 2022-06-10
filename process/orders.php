<?php

    include_once("conn.php");

    $method = $_SERVER["REQUEST_METHOD"];

    if ($method === "GET"){

        $pedidosQuery = $conn->query("SELECT * FROM pedidos;");

        $pedidos = $pedidosQuery->fetchAll();

        $pizzas = [];

        // Montando as pizzas
        foreach($pedidos as $pedido) {

            $pizza = [];

            // Define um array para a pizzza
            $pizza["id"] = $pedido["pizza_id"];

            // Resgatando a pizza
            $pizzaQuery = $conn->prepare("SELECT * FROM pizzas WHERE id = :pizza_id");

            $pizzaQuery->bindParam(":pizza_id", $pizza["id"]);

            $pizzaQuery->execute();

            $pizzaData = $pizzaQuery->fetch(PDO::FETCH_ASSOC);

            // Resgatando a borda
            $bordaQuery = $conn->prepare("SELECT * FROM bordas WHERE id = :borda_id");

            $bordaQuery->bindParam(":borda_id", $pizzaData["borda_id"]);

            $bordaQuery->execute();

            $borda = $bordaQuery->fetch(PDO::FETCH_ASSOC);

            $pizza["borda"] = $borda["tipo"];

            // Resgatando a massa
            $massaQuery = $conn->prepare("SELECT * FROM massas WHERE id = :massa_id");

            $massaQuery->bindParam(":massa_id", $pizzaData["massa_id"]);

            $massaQuery->execute();

            $massa = $massaQuery->fetch(PDO::FETCH_ASSOC);

            $pizza["massa"] = $massa["tipo"];

            // Resgatando os sabores
            $saboresQuery = $conn->prepare("SELECT * FROM pizza_sabor WHERE pizza_id = :pizza_id");

            $saboresQuery->bindParam(":pizza_id", $pizza["id"]);

            $saboresQuery->execute();

            $sabores = $saboresQuery->fetchAll(PDO::FETCH_ASSOC);

            // Resgatando o nome dos sabores
            $saboresDaPizza = [];

            $saborQuery = $conn->prepare("SELECT * FROM sabores WHERE id = :sabor_id");
            
            foreach($sabores as $sabor) {

                $saborQuery->bindParam(":sabor_id", $sabor["sabor_id"]);

                $saborQuery->execute();

                $saborPizza = $saborQuery->fetch(PDO::FETCH_ASSOC);

                array_push($saboresDaPizza, $saborPizza["nome"]);
            }

            $pizza["sabores"] = $saboresDaPizza;

            // Adicionar o status do pedido
            $pizza["status"] = $pedido["status_id"];

            // Adicionando o array de pizza, ao array das pizza
            array_push($pizzas, $pizza);

            // Regatando os status dos pedidos
            $statusQuery = $conn->query("SELECT * FROM status;");

            $status = $statusQuery->fetchAll();
        }

    } else if ($method === "POST") {
        
        // Verificando o POST
        $type = $_POST["type"];

        // Deletando o pedido
        if($type === "delete") {

            $pizzaId = $_POST["id"];

            $deleteQuery = $conn->prepare("DELETE FROM pedidos WHERE pizza_id = :pizza_id;");

            $deleteQuery->bindParam(":pizza_id", $pizzaId, PDO::PARAM_INT);

            $deleteQuery->execute();

            $_SESSION["msg"] = "Pedido removido com sucesso!";
            $_SESSION["status"] = "success";
        }
        
        // Retorna o usuário para dashboard
        header("Location: ../dashboard.php");
    }

?>