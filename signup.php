<?php

require_once 'myconfig.php';


if (Input::exists()) {
    if (Token::check(Input::get('token'))) {

        $validate  = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array(
                'required' => true,
                'min' => 2,
                'max' => 20,
                'unique' => 'users'
            ),
            'email' => array(
                'required' => true,
                'min' => 2,
                'max' => 50,
                'is_email' => true,
                'unique' => 'users'
            ),
            'password_again' => array(
                'required' => true,
                'matches' => 'password'
            ),
            'password' => array(
                'required' => true,
                'min' => 6
            )
        ));

        if ($validation->passed()) {
            $user = new User();
            try {
                $user->create(array(
                    'username' => Input::get('username'),
                    'password' => Hash::make(Input::get('password')),
                    'email' => Input::get('email')
                ));
                Session::flash('home', 'You have been registered and can now log in!');
                Redirect::to('index.php');
            } catch (Exception $e) {
                die($e->getMessage());
            }
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

    <title>Sign UP</title>
</head>

<body>

</body>

</html>

<section class="signup">
    <div class="container">
        <div class="signup-content">
            <div class="signup-form">
                <h2 class="form-title">Sign up</h2>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="username"><i class="zmdi zmdi-account material-icons-name"></i></label>
                        <input type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off" placeholder="Your Name">
                    </div>
                    <div class="form-group">
                        <label for="email"><i class="zmdi zmdi-email"></i></label>
                        <input type="text" name="email" id="email" value="<?php echo escape(Input::get('email')); ?>" placeholder="Your Email">
                    </div>
                    <div class="form-group">
                        <label for="password"><i class="zmdi zmdi-lock"></i></label>
                        <input type="password" name="password" id="password" placeholder="Your Password">
                    </div>
                    <div class="form-group">
                        <label for="password_again"><i class="zmdi zmdi-lock-outline"></i></label>
                        <input type="password" name="password_again" id="password_again" placeholder="Confirm your Password">
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="agree-term" id="agree-term" class="agree-term" />
                        <label for="agree-term" class="label-agree-term"><span><span></span></span>I agree all statements in <a href="#" class="term-service">Terms of service</a></label>
                    </div>
                    <div class="form-group form-button">
                        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                        <input class="btn btn-primary" type="submit" value="Register" class="form-submit">

                    </div>
                </form>
            </div>
            <div class="signup-image">
                <figure><img src="../images/signup-image.jpg" alt="sing up image"></figure>
                <a href="signin.php" class="signup-image-link">I am already member</a>
            </div>
        </div>
    </div>
</section>