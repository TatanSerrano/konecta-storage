<?php
$config = include "config.php";
$result = [
    "error" => false,
    "message" => ""
];
try {
    $dsn = "mysql:host=" . $config["db"]["host"] . ";dbname=". $config["db"]["name"];
    $connection = new PDO($dsn, $config["db"]["user"], $config["db"]["pass"], $config["db"]["options"]);
    $requestSQL = 
    "SELECT sale.sale_id, product.nombre, sale.quantity, product.precio 
    FROM sale
    INNER JOIN product ON product.product_id=sale.product_id";
    $sentence = $connection->prepare($requestSQL);
    $sentence->execute();
    $sales = $sentence->fetchAll();
} catch (PDOException $error) {
    $result["error"] = true;
    $result["message"] = $error->getMessage();
}
?>
<?php require "templates/header.php" ?>
<?php
if($error){
    ?>
    <div class="container mt-2">
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger" role="alert">
                    <?= $error ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <h3 class="mt-3">Ventas</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($sales && $sentence->rowCount() > 0){
                        $total = 0;
                        foreach($sales as $sale){
                            ?>
                            <tr>
                                <td><?php echo $sale["sale_id"]?></td>
                                <td><?php echo $sale["nombre"]?></td>
                                <td><?php echo $sale["quantity"]?></td>
                                <td>$<?php echo $sale["precio"]*$sale["quantity"]?></td>
                            </tr>
                            <?php
                            $total += $sale["precio"]*$sale["quantity"];
                        }
                    }
                    ?>
                </tbody>
            </table>
            <p>Total ventas: <b>$<?=$total?></b></p>
        </div>
    </div>
</div>
<?php require "templates/footer.php" ?>