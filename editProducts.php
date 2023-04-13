<?php

require_once 'myconfig.php';

$admin = new User;

if ($admin->data()->admin == true) {
    if (Input::exists()) {
        if (Token::check(Input::get('token'))) {

            $validate  = new Validate();
            $validation = $validate->check($_POST, array(
                'name_edit' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 100
                ),
                'price_edit' => array(
                    'required' => true
                ),
                'description_edit' => array(
                    'required' => true
                )
            ));

            $targetDir = "../images/";
            $fileName = basename($_FILES["file_edit"]["name"]);

            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            if (isset($_POST["submit"]) && !empty($_FILES["file_edit"]["name"])) {
                $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'pdf');
                if (in_array($fileType, $allowTypes)) {
                    if (move_uploaded_file($_FILES["file_edit"]["tmp_name"], $targetFilePath)) {
                    }
                }
            }


            if ($validation->passed()) {
                if (!empty($_FILES['file_edit']['name'])) {
                    if (Input::get('modify_product')) {
                        $product = DB::getInstance()->update('products', Input::get('modify_product'), array(
                            'name' => Input::get('name_edit'),
                            'price' => Input::get('price_edit'),
                            'description' => Input::get('description_edit'),
                            'image' => $fileName

                        ));
                        Redirect::to('showProducts.php');
                    }
                } else {

                    if (Input::get('modify_product')) {
                        $product = DB::getInstance()->update('products', Input::get('modify_product'), array(
                            'name' => Input::get('name_edit'),
                            'price' => Input::get('price_edit'),
                            'description' => Input::get('description_edit')

                        ));
                        Redirect::to('showProducts.php');
                    }
                }
            } else {
                foreach ($validation->errors() as $error) {
                    echo $error, '<br>';
                }
            }
        }
    }


    $product = DB::getInstance()->get('products', array('id', "=", Input::get('modify_product')));

    $result = $product->results();

    foreach ($result as $value) {

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
                                <label for="name_edit"><i class="zmdi zmdi-label material-icons"></i></label>
                                <input type="text" name="name_edit" id="name_edit" autocomplete="off" placeholder="Product Name" value="<?php echo $value->name; ?>">
                            </div>
                            <div class="form-group">
                                <label for="price_edit"><i class="zmdi zmdi-money-box"></i></label>
                                <input type="text" name="price_edit" id="price_edit" placeholder="Product Price" value="<?php echo $value->price; ?>">
                            </div>
                            <div class="form-group">
                                <label for="description_edit"><i class="zmdi zmdi-file-txt"></i></label>
                                <textarea class="textarea-product" name="description_edit" id="description_edit"><?php echo $value->description; ?></textarea>
                            </div>
                            <div class="form-group">
                                <input type="file" name="file_edit">
                            </div>
                            <div class="form-group form-button">
                                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                                <input class="btn btn-primary" name="submit" type="submit" value="Update" class="form-submit">
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
    }
} else {
    Redirect::to('index.php');
}
