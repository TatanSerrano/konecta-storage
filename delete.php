<?php
$config = include "config.php";
$result = [
    "error" => false,
    "message" => ""
];

try {
    $dsn = "mysql:host=".$config["db"]["host"].";dbname=".$config["db"]["name"];
    $connection = new PDO($dsn,$config["db"]["user"],$config["db"]["pass"],$config["db"]["options"]);
    $id = $_GET["id"];
    $requestSQL = "DELETE FROM sale WHERE product_id =".$id;
    $sentence = $connection->prepare($requestSQL);
    $sentence->execute();
    
    $requestSQL = "DELETE FROM product WHERE product_id =".$id;
    $sentence = $connection->prepare($requestSQL);
    $sentence->execute();
    header("Location: index.php");
} catch (PDOException $error) {
    $result["error"] = true;
    $result["message"] = $error->getMessage();
}
?>
<?php require "templates/header.php"?>
<div class="container mt-2">
    <div class="row">
        <div class="col-md-12">
            <div class="alert-alert-danger" role="alert">
                <?=$result["message"]?>
            </div>
        </div>
    </div>
</div>
<?php require "templates/footer.php"?>