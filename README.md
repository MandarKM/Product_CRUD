<?php
$pdo = new PDO("mysql:host=localhost; port=3306; dbname=products_crud", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$search = $_GET["search"] ?? "";
if ($search) {
    $statement = $pdo->prepare("SELECT * FROM products WHERE title LIKE :title ORDER BY create_date DESC");
    $statement->bindValue(":title", "%$search%");
} else {
    $statement = $pdo->prepare("SELECT * FROM products ORDER BY create_date DESC");
}
$statement->execute();
$products = $statement->fetchAll(PDO::FETCH_ASSOC);


?>



<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product_CRUD</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="crud.css">

<body>
    <h1>Product_CRUD</h1>
    <p>
        <a href="create.php"><button class="btn btn-success">Create Product</button></a>
    </p>


    <form>
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search for products" name="search" value="<?php echo $search ?>">
            <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
        </div>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Image</th>
                <th scope="col">Title</th>
                <th scope="col">Price</th>
                <th scope="col">Create Date</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $i => $product) : ?>
                <tr>
                    <th scope="row"><?php echo $i + 1 ?></th>
                    <td><img src="<?php echo $product["image"] ?>" class="thumb-image"></td>
                    <td><?php echo $product["title"] ?></td>
                    <td><?php echo $product["price"] ?></td>
                    <td><?php echo $product["create_date"] ?></td>
                </tr>
                <td>
                    <a href="update.php?id=<?php echo $product["id"] ?>" type="button" class="btn btn-sm btn-dark">Edit</a>
                    <form style="display: inline-block" action="delete.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $product["id"] ?>">
                        <button type="submit" href="delete.php?id=<?php echo $product['id'] ?>" type="button" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>

            <?php endforeach; ?>

        </tbody>
    </table>
</body>

</html>
