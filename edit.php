<?php
$config = include "config.php";
$result = [
    "error" => false,
    "message" => ""
];

if(!isset($_GET["id"])){
    $result["error"] = true;
    $result["message"] = "El producto no existe";
}

if(isset($_POST["submit"])){
    try {
        $dsn = "mysql:host=".$config["db"]["host"].";dbname=".$config["db"]["name"];
        $connection = new PDO($dsn,$config["db"]["user"],$config["db"]["pass"],$config["db"]["options"]);
        $product = [
            "product_id" => $_GET["id"],
            "nombre" => $_POST["name"],
            "referencia" => $_POST["reference"],
            "precio" => $_POST["price"],
            "peso" => $_POST["weight"],
            "categoria" => $_POST["category"],
            "stock" => $_POST["stock"]
        ];
        $requestSQL = "UPDATE product SET
            nombre = :nombre,
            referencia = :referencia,
            precio = :precio,
            peso = :peso,
            categoria = :categoria,
            stock = :stock
            WHERE product_id = :product_id";
        $sentence = $connection->prepare($requestSQL);
        $sentence->execute($product);
        $result["message"] = "El producto ha sido actualizado correctamente";
    } catch (PDOException $error) {
        $result["error"] = true;
        $result["message"] = $error->getMessage();
    }
}
try {
    $dsn = "mysql:host=".$config["db"]["host"].";dbname=".$config["db"]["name"];
    $connection = new PDO($dsn,$config["db"]["user"],$config["db"]["pass"],$config["db"]["options"]);
    $id = $_GET["id"];
    $requestSQL = "SELECT * FROM product WHERE product_id =".$id;
    $sentence = $connection->prepare($requestSQL);
    $sentence->execute();

    $product = $sentence->fetch(PDO::FETCH_ASSOC);

    if(!$product){
        $result["error"] = true;
        $result["message"] = "No se encontro el producto";
    }
} catch (PDOException $error) {
    $result["error"] = true;
    $result["message"] = $error->getMessage();
}
?>
<?php require "templates/header.php";?>
<?php
if(isset($_POST["submit"])|| $result["error"]){
    ?>
    <div class="container mt-2">
        <div class="row">
            <div class="col-12">
                <div class="alert alert-<?= $result["error"]? 'danger':'success'?>" role="alert">
                    <?= $result["message"]?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>
<?php
if(isset($product)){
    ?>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="mt-4">Editando el producto: <?=$product["nombre"]?></h3>
                <hr>
                <form method="post" class="row g-3">
                    <div class="form-group col-md-6">
                        <label for="name">Nombre</label>
                        <input type="text" name="name" id="name" class="form-control"
                        value="<?= $product["nombre"]?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="reference">Referencia</label>
                        <input type="text" name="reference" id="reference" class="form-control"
                        value="<?=$product["referencia"]?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="price">Precio</label>
                        <input type="number" name="price" id="price" class="form-control"
                        value="<?=$product["precio"]?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="weight">Peso</label>
                        <input type="number" name="weight" id="weight" class="form-control"
                        value="<?=$product["peso"]?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="category">Categoria</label>
                        <input type="text" name="category" id="category" class="form-control"
                        value="<?=$product["categoria"]?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="stock">Stock</label>
                        <input type="number" name="stock" id="stock" class="form-control"
                        value="<?=$product["stock"]?>">
                    </div>
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-primary">
                            Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
}
?>
<?php require "templates/footer.php";?>