<?php

require_once 'myconfig.php';
$admin = new User;

if ($admin->data()->admin == true) {
    $user = DB::getInstance()->getAll('SELECT *', 'products');

    $result = $user->results();



?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- CSS only -->
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- JS, Popper.js, and jQuery -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        <title>Products</title>
    </head>

    <body>

    </body>

    </html>

    <div class="container-xl">
        <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-sm-6">
                            <h2 class="titre-admin">Manage <b>Product</b></h2>
                        </div>
                        <div class="col-sm-6">
                            <a href="newProduct.php"><button class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i><span>Add New Product</span></button></a>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Category ID</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Delete</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <?php
                    foreach ($result as $value) {
                        $imageUrl = '../images/' . $value->image;
                    ?>

                        <tbody>
                            <tr>
                                <td><?php echo $value->id; ?></td>
                                <td><?php echo $value->name; ?></td>
                                <td><?php echo $value->price; ?></td>
                                <td><?php echo $value->category_id; ?></td>
                                <td><?php echo $value->description; ?></td>
                                <td><img src="<?php echo $imageUrl; ?>" alt="product_image"></td>
                                <td>
                                    <form action='deleteProducts.php' method='POST'>
                                        <input type="hidden" name="delete_product" value="<?php echo $value->id ?>">
                                        <button type="submit" class="delete btn btn-danger" data-toggle="modal" name="delete_product_btn"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></button>
                                    </form>
                                </td>
                                <td>
                                    <form action='editProducts.php' method='GET'>
                                        <input type="hidden" name="modify_product" value="<?php echo $value->id ?>">
                                        <button type="submit" class="edit btn btn-warning"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></button>
                                    </form>
                                </td>
                            </tr>

                        <?php
                    } ?>
                </table>

                <a href="Admin.php"><button class="btn-block btn-lg btn-primary" type="submit" name="product_list">Return to admin page</button></a>

            <?php
        } else {
            Redirect::to('index.php');
        }

            ?>