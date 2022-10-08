<?php
$pdo = new PDO("mysql:host=localhost; port=3306; dbname=products_crud", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$errors = [];

$title = "";
$price = "";
$description = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $date = date('Y-m-d H:i:s');

    if (!$title) {
        $errors[] = "Please enter title";
    }
    if (!$price) {
        $errors[] = "Please enter price";
    }

    if (!is_dir("images")) {
        mkdir("images");
    }

    if (empty($errors)) {
        $image = $_FILES["image"] ?? null;
        $imagePath = "";
        if ($image  && $image["tmp_name"]) {

            $imagePath = "images/" . randomString(8) . '/' . $image["name"];
            mkdir(dirname($imagePath));
            move_uploaded_file($image["tmp_name"], $imagePath);
        }

        $statement = $pdo->prepare("INSERT INTO products (title, image, description, price,create_date)
            VALUES (:title, :image , :description, :price, :date )");
        $statement->bindValue(':title', $title);
        $statement->bindValue(':image', $imagePath);
        $statement->bindValue(':description', $description);
        $statement->bindValue(':price', $price);
        $statement->bindValue(':date', $date);
        $statement->execute();
        header("Location: index.php");
    }
}
function randomString($n)
{
    $characters = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $str = "";
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $str .= $characters[$index];
    }
    return $str;
}

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
    <h1>New_Product</h1>
    <?php if (!empty($errors)) : ?>
        <div class="alert alert-danger" role="alert">
            <?php foreach ($errors as $error) : ?>
                <div><?php echo $error ?></div>
            <?php endforeach ?>
        </div>
    <?php endif ?>


    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Product Image</label>
            <input type="file" name="image"><br>
        </div>
        <div class="mb-3">
            <label class="form-label"> Product Title</label>
            <input type="text" name="title" class="form-control" value="<?php echo $title ?>">
        </div>
        <div class="mb-3">
            <label class="form-label"> Product Discription</label>
            <input class="form-control" name="description" value="<?php echo $description ?>">
        </div>
        <div class="mb-3">
            <label class="form-label"> Product Price</label>
            <input type="number" class="form-control" name="price" value="<?php echo $price ?>">
        </div>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</body>

</html>