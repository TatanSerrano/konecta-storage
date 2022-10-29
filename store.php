<?php
$config = include "config.php";
$result = [
    "error" => false,
    "message" => ""
];
if (isset($_POST["submit"])) {
    if ($_POST["stock"] - $_POST["count"] >= 0) {
        try {
            $dsn = "mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["name"];
            $connection = new PDO($dsn, $config["db"]["user"], $config["db"]["pass"], $config["db"]["options"]);
            $product = [
                "product_id" => $_POST["submit"],
                "stock" => $_POST["stock"] - $_POST["count"]
            ];
            $requestSQL = "UPDATE Product SET
            stock = :stock
            WHERE product_id = :product_id";
            $sentence = $connection->prepare($requestSQL);
            $sentence->execute($product);

            $sale = [
                "quantity" => $_POST["count"],
                "product_id" => $_POST["submit"]
            ];
            $requestSQL = "INSERT INTO Sale (quantity, product_id)";
            $requestSQL .= "values (:" . implode(", :", array_keys($sale)) . ")";
            $sentence = $connection->prepare($requestSQL);
            $sentence->execute($sale);
        } catch (PDOException $error) {
            $result["error"] = true;
            $result["message"] = $error->getMessage();
        }
    }else{
        $result["error"] = true;
        $result["message"] = "No hay más unidades disponibles de ".$_POST["nombre"];
    }
}
try {
    $dsn = "mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["name"];
    $connection = new PDO($dsn, $config["db"]["user"], $config["db"]["pass"], $config["db"]["options"]);
    $requestSQL = "SELECT * FROM product";
    $sentence = $connection->prepare($requestSQL);
    $sentence->execute();
    $products = $sentence->fetchAll();
} catch (PDOException $error) {
    $result["error"] = true;
    $result["message"] = $error->getMessage();
}
?>
<?php require "templates/header.php" ?>
<?php
if ($result["error"]) {
?>
    <div class="container mt-2">
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger" role="alert">
                    <?= $result["message"] ?>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>
<section class="container mt-4">
    <div class="row gx-5">
        <?php foreach ($products as $product) { ?>
            <div class="card col-md-4">
                <div class="card-body">
                    <h4><?= $product["nombre"] ?></h4>
                    <p><?= $product["peso"] ?> gramos</p>
                    <p>Precio: <span>$<?= $product["precio"] ?></span></p>
                    <p>Stock: <span><?= $product["stock"] ?></span></p>
                    <form method="post" class="row">
                        <div class="form-group col-md-8">
                            <label for="count">Cantidad</label>
                            <input min="1" max="<?= $product["stock"]+1 ?>" type="number" id="count" name="count" class="form-control" value="1">
                        </div>
                        <input type="hidden" name="nombre" value="<?= $product["nombre"] ?>">
                        <input type="hidden" name="stock" value="<?= $product["stock"] ?>">
                        <button type="submit" class="btn btn-primary col-md-4 mt-4" name="submit" value="<?= $product["product_id"] ?>">Comprar</a>
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>
</section>
<?php require "templates/footer.php" ?>