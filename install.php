<?php
$config = include "config.php";

try{
    $connection = new PDO(
        "mysql:host=" . $config["db"]["host"],
        $config["db"]["user"],
        $config["db"]["pass"],
        $config["db"]["options"]
    );
    $sql = file_get_contents("data/db.sql");
    $connection->exec($sql);
    echo "Se ha creado la base de datos con éxito";
}catch(PDOException $error){
    echo $error->getMessage();
}
