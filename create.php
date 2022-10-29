<?php
if (isset($_POST["submit"])) {
    $result = [
        "error" => false,
        "message" => "Producto agregado con éxito"
    ];
    $config = include "config.php";
    try {
        $dsn = "mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["name"];
        $connection = new PDO($dsn, $config["db"]["user"], $config["db"]["pass"], $config["db"]["options"]);
        $product = [
            "nombre" => $_POST["name"],
            "referencia" => $_POST["reference"],
            "precio" => $_POST["price"],
            "peso" => $_POST["weight"],
            "categoria" => $_POST["category"],
            "stock" => $_POST["stock"]
        ];
        $requestSQL = "INSERT INTO product (nombre, referencia, precio, peso, categoria, stock)";
        $requestSQL .= "values (:" . implode(", :", array_keys($product)) . ")";
        $sentence = $connection->prepare($requestSQL);
        $sentence->execute($product);
    } catch (PDOException $error) {
        $result["error"] = true;
        $result["message"] = $error->getMessage();
    }
}
?>
<?php include "templates/header.php"; ?>
<?php
if (isset($result)) {
?>
    <div class="container mt-3">
        <div class="row">
            <div class="col-12">
                <div class="alert alert-<?= $result["error"] ? "danger" : "success" ?>" role="alert">
                    <?= $result["message"] ?>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>

<section class="container">
    <form method="post" class="row g-3">
        <h1>Añadir producto</h1>
        <div class="col-12">
            <label for="" class="form-label">Nombre de producto:</label>
            <input type="text" class="form-control" name="name">
        </div>
        <div class="col-md-6">
            <label for="" class="form-label">Referencia:</label>
            <input type="text" class="form-control" name="reference">
        </div>
        <div class="col-md-6">
            <label for="" class="form-label">Precio:</label>
            <input type="number" class="form-control" name="price">
        </div>
        <div class="col-md-6">
            <label for="" class="form-label">Peso:</label>
            <input type="text" class="form-control" name="weight">
        </div>
        <div class="col-md-6">
            <label for="" class="form-label">Categoría:</label>
            <input type="text" class="form-control" name="category">
        </div>
        <div class="col-12">
            <label for="" class="form-label">Stock:</label>
            <input type="number" class="form-control" name="stock">
        </div>
        <div class="col-12">
            <button type="submit" name="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>
</section>
<?php include "templates/footer.php"; ?>