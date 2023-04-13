<?php

require_once "myconfig.php";

if (Session::exists('home')) {
    echo Session::flash('home');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../fonts/material-icon/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
    <title>Welcome</title>
</head>

<body>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-light ">
            <img src="../images/logo.png" alt="logo brand" class="logo">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Home<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact Us</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0" method="post" action="search.php">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" name="search">
                    <button class="btn btn-outline-dark my-2 my-sm-0 search-button" type="submit" name="submit_search">Search</button>
                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="zmdi zmdi-account zmdi-hc-1x"></i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="profil.php">Profile</a>
                        <a class="dropdown-item" href="signin.php">Sign in</a>
                        <a class="dropdown-item" href="logout.php">Log out</a>
                    </div>
            </div>
            <div class="picture-profile-index">
                <?php $user = new User();

                if ($user->isLoggedIn()) {
                    $imageUrl = '../images/profiles/' . $user->data()->image;
                ?><img src="<?php echo $imageUrl ?>" class="img-circle">
                <?php } ?>
            </div>
            <a class="profile" href="panier.php"><i class="zmdi zmdi-shopping-cart zmdi-hc-2x"></i></a>
            </form>
    </div>
    </nav>
    </div>
    <div class="container-fluid product">
        <div class="grid">
            <div class="row">
                <?php

                $user = DB::getInstance()->getAll('SELECT *', 'products');

                $result = $user->results();


                foreach ($result as $value) {
                    $imageUrl = '../images/' . $value->image;
                ?>
                    <div class="card col-lg-3 col-md-6 col-sm-12" style="width: 18rem;">
                        <div class="img-hover-zoom">
                            <img src="<?php echo $imageUrl; ?>" alt="product_image" class="card-img-top " alt="...">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $value->name ?></h5>
                            <h5 class="card-title"><?php echo $value->description ?></h5>

                            <p class="card-text card-price"><?php echo $value->price . " €" ?></p>
                            <form method="GET" action="">
                                <input type="hidden" name="id" value="<?php echo $value->id ?>" />
                                <button type="submit"><i class="zmdi zmdi-shopping-cart zmdi-hc-1x"></i></button>
                            </form>
                        </div>
                    </div>
                <?php





                    $id = $_GET['id'];

                    if (!isset($_SESSION['cart'])) {
                        $_SESSION['cart'] = array();
                    }

                    if (isset($_GET["id"])) {
                        if (!in_array($_GET["id"], $_SESSION['cart'])) {
                            $_SESSION['cart'][] = $_GET["id"];
                        }
                    }
                } ?>
            </div>
        </div>
    </div>

    </div>
</body>

</html>