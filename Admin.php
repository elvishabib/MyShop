<?php

require_once 'myconfig.php';

$admin = new User;

if ($admin->data()->admin == true) {

    $user = DB::getInstance()->getAll('SELECT *', 'users');

    $result = $user->results();


?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        <title>Admin</title>
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-white bg-white">
            <div class="container px-lg-5">
                <a class="navbar-brand" href="Admin.php">Admin Dashboard</a>
                <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expended="false" aria-label="Toggle navigation" ><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Shop View</a>
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="logout.php">Log Out</a>
                    </ul>
                </div> -->
                <a href="logout.php"><button type="button" class="btn btn-outline-danger" name="product_list">LOG OUT</button></a>

            </div>
        </nav>
        <div class="container px-lg-5">
            <div class="p-4 p-lg-5 bg-light rounded-3 text-center">
            <h1 class="alert-success"> Hello <?php echo $admin->data()->username ?> ! </h1>
            <h2>WELCOME TO YOUR ADMINISTRATION CRUD <h2>
                   <!--  <a href="#"><button type="button" class="btn btn-outline-primary" name="product_list">SHOW CATEGORIES</button></a> -->
                    <a href="showProducts.php"><button type="button" class="btn btn-outline-primary" name="product_list">SHOW PRODUCTS</button></a>
                    <a href="showUsers.php"><button type="button" class="btn btn-outline-primary" name="product_list">SHOW USERS</button></a>
                    <a href="index.php"><button type="button" class="btn btn-outline-success" name="product_list">SHOP VIEW</button></a>
                    <!-- <a href="logout.php"><button type="button" class="btn btn-outline-danger" name="product_list">LOG OUT</button></a> -->

        </div>
        </div>
    </body>

    </html>

<?php
} else {
    Redirect::to('index.php');
}

?>