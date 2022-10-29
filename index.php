<?php
$error = false;
$config = include "config.php";

try {
    $dsn = "mysql:host=".$config["db"]["host"].";dbname=".$config["db"]["name"];
    $connection = new PDO($dsn,$config["db"]["user"],$config["db"]["pass"],$config["db"]["options"]);
    if(isset($_POST["product-name"])){
        $requestSQL = "SELECT * FROM product WHERE nombre LIKE '%".$_POST["product-name"]."%'";
    }else{
        $requestSQL = "SELECT * FROM product";
    }
    $sentence = $connection->prepare($requestSQL);
    $sentence->execute();
    $products = $sentence->fetchAll();

    $requestSQL = 
    "SELECT * FROM product
    WHERE product.stock = (SELECT MAX(stock) FROM product)";
    $sentence = $connection->prepare($requestSQL);
    $sentence->execute();
    $product_max_stock = $sentence->fetchAll();

    $requestSQL = 
    "SELECT SUM(quantity) AS quantity, product.nombre, sale.product_id FROM sale
    INNER JOIN product ON product.product_id=sale.product_id
    GROUP BY product_id
    ORDER BY SUM(quantity) DESC
    LIMIT 1";
     $sentence = $connection->prepare($requestSQL);
     $sentence->execute();
     $product_max_sell = $sentence->fetchAll();
} catch (PDOException $_error) {
    $error = $_error->getMessage();
}
?>
<?php include "templates/header.php";?>
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
<section class="container">
    <div class="row">
        <p class="mt-4">Bienvenidos a Konecta Storage</p>
        <div class="col-12">
            <a href="create.php" class="btn btn-primary mt-4">Crear producto</a>
            <hr>
            <form method="post" class="row row-cols-lg-auto align-items-center g-3">
                <div class="form-group col-12">
                    <input type="text" class="form-control" name="product-name" placeholder="Buscar por nombre">
                </div>
                <div class="col-12">
                    <button type="submit" name="submit" class="btn btn-primary">Buscar</button>
                </div>
            </form>
        </div>
    </div>
</section>
<section class="container">
    <div class="row">
        <div class="col-12">
            <h3 class="mt-3">Productos</h3>
            <ul>
                <li><b>Producto con más Stock:</b> <?= $product_max_stock[0]["nombre"]?> con <?=$product_max_stock[0]["stock"]?> unidades.</li>
                <li><b>Producto más vendido: </b> <?= $product_max_sell[0]["nombre"]?> con <?=$product_max_sell[0]["quantity"]?> ventas.</li>
            </ul>
            <table class="table">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Nombre</th>
                        <th>Referencia</th>
                        <th>Precio</th>
                        <th>Peso</th>
                        <th>Categoría</th>
                        <th>Stock</th>
                        <th>Fecha de creación</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($products && $sentence->rowCount() > 0){
                        foreach($products as $product){
                            ?>
                            <tr>
                                <td><?php echo $product["product_id"];?></td>
                                <td><?php echo $product["nombre"];?></td>
                                <td><?php echo $product["referencia"];?></td>
                                <td><?php echo $product["precio"];?></td>
                                <td><?php echo $product["peso"];?></td>
                                <td><?php echo $product["categoria"];?></td>
                                <td><?php echo $product["stock"];?></td>
                                <td><?php echo $product["created_at"];?></td>
                                <td>
                                    <a href="<?='delete.php?id='.$product["product_id"]?>">Borrar</a>
                                    <a href="<?='edit.php?id='.$product["product_id"]?>">Editar</a>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php include "templates/footer.php";?>