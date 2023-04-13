<?php

require_once 'myconfig.php';
$admin = new User;

if ($admin->data()->admin == true) {
    if (Input::exists()) {
        if (Token::check(Input::get('token'))) {

            $validate  = new Validate();
            $validation = $validate->check($_POST, array(
                'username_edit' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 20,
                ),
                'email_edit' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 50,
                    'is_email' => true,
                ),

                'password_again_edit' => array(
                    'matches' => 'password_edit',

                ),
                'password_edit' => array(
                    'min' => 6,

                )
            ));

            if ($validation->passed()) {
                if (isset($_POST['admin'])) {
                    $rights = 1;
                } else {
                    $rights = 0;
                }


                if (Input::get('modify')) {
                    if (empty($_POST['password_edit']) && empty($_POST['password_again_edit'])) {

                        $user = DB::getInstance()->get('users', array('id', '=', Input::get('modify')));
                        $mdp = $user->results();
                        $hash = $mdp->password;

                        $user = DB::getInstance()->update('users', Input::get('modify'), array(
                            'username' => Input::get('username_edit'),
                            'email' => Input::get('email_edit'),
                            'admin' => $rights
                        ));
                        Redirect::to('showUsers.php');
                    } else {


                        $user = DB::getInstance()->update('users', Input::get('modify'), array(
                            'username' => Input::get('username_edit'),
                            'email' => Input::get('email_edit'),
                            'password' => Hash::make(Input::get('password_edit')),
                            'admin' => $rights
                        ));
                        Redirect::to('showUsers.php');
                    }
                } else {
                    foreach ($validation->errors() as $error) {
                        echo $error, '<br>';
                    }
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

        <title>Sign UP</title>
    </head>

    <body>

        <?php

        $info = DB::getInstance()->get('users', array('id', "=", Input::get('modify')));

        $result = $info->results();

        foreach ($result as $value) {

        ?>


            <section class="signup">
                <div class="container">
                    <div class="signup-content">
                        <div class="signup-form">
                            <h2 class="form-title">Update User</h2>
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="username_edit"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                    <input type="text" name="username_edit" id="username_edit" value="<?php echo $value->username ?>" autocomplete="off" placeholder="Your Name">
                                </div>
                                <div class="form-group">
                                    <label for="email_edit"><i class="zmdi zmdi-email"></i></label>
                                    <input type="text" name="email_edit" id="email_edit" value="<?php echo $value->email ?>" placeholder="Your Email">
                                </div>
                                <div class="form-group">
                                    <label for="password_edit"><i class="zmdi zmdi-lock"></i></label>
                                    <input type="password" name="password_edit" id="password_edit" placeholder="Your Password">
                                </div>
                                <div class="form-group">
                                    <label for="password_again_edit"><i class="zmdi zmdi-lock-outline"></i></label>
                                    <input type="password" name="password_again_edit" id="password_again_edit" placeholder="Confirm your Password">
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="admin" id="admin" value="yes" <?php echo ($value->admin == 1 ? 'checked' : ''); ?> class="agree-term" />
                                    <label for="admin" class="label-agree-term"><span><span></span></span>Admin Rights</label>
                                </div>
                                <div class="form-group form-button">
                                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                                    <input class="btn btn-primary" type="submit" value="update" name="update" class="form-submit">
                                </div>
                            </form>
                        </div>
                        <div class="signup-image">
                            <figure><img src="../images/signup-image.jpg" alt="sing up image"></figure>
                            <a href="Admin.php" class="signup-image-link">Admin CRUD</a>
                        </div>
                    </div>
                </div>
            </section>

    </body>

    </html>


<?php
        }
    } else {
        Redirect::to('index.php');
    }
