<?php

require_once 'myconfig.php';
$admin = new User;

if ($admin->data()->admin == true) {

    if (Input::exists()) {
        if (Token::check(Input::get('token'))) {

            $validate  = new Validate();
            $validation = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 100,
                    'unique' => 'products'
                ),
                'price' => array(
                    'required' => true,
                    'int' => true
                ),
                'description' => array(
                    'required' => true
                )
            ));

            $targetDir = "../images/";
            $fileName = basename($_FILES["file"]["name"]);
            if($fileName==NULL) 
            $fileName = "product.png";
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            if (isset($_POST["submit"]) && !empty($_FILES["file"]["name"])) {
                $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'pdf');
                if (in_array($fileType, $allowTypes)) {
                    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
                    }
                }
            }


            if ($validation->passed()) {
                $product = new Product();
                $product->createProduct(array(
                    'name' => Input::get('name'),
                    'price' => Input::get('price'),
                    'description' => Input::get('description'),
                    'image' => $fileName
                ));
                Redirect::to('showProducts.php');
            } else {
                foreach ($validation->errors() as $error) {
                    echo $error, '<br>';
                }
            }
        }
    }

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../fonts/material-icon/css/material-design-iconic-font.min.css">

        <title>New Product</title>
    </head>

    <body>

    </body>

    </html>

    <section class="signup">
        <div class="container">
            <div class="signup-content">
                <div class="signup-form">
                    <h2 class="form-title">New Product</h2>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name"><i class="zmdi zmdi-label material-icons"></i></label>
                            <input type="text" name="name" id="name" autocomplete="off" placeholder="Product Name">
                        </div>
                        <div class="form-group">
                            <label for="price"><i class="zmdi zmdi-money-box"></i></label>
                            <input type="text" name="price" id="price" placeholder="Product Price">
                        </div>
                        <div class="form-group">
                            <label for="description"><i class="zmdi zmdi-file-txt"></i></label>
                            <textarea class="textarea-product" name="description" id="description"></textarea>
                        </div>
                        <div class="form-group">
                            <input type="file" name="file">
                        </div>
                        <div class="form-group form-button">
                            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                            <input class="btn btn-primary" name="submit" type="submit" value="Create" class="form-submit">
                        </div>
                    </form>
                </div>
                <div class="signup-image">
                    <figure><img src="../images/font_add.jpg" alt="new product image"></figure>
                    <a href="Admin.php" class="signup-image-link">Admin CRUD</a>
                </div>
            </div>
        </div>
    </section>
<?php
} else {
    Redirect::to('index.php');
}
