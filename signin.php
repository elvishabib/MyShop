<?php

require_once 'myconfig.php';

$user = new User();

if ($user->isLoggedIn()) {
    Redirect::to('index.php');
} else {

    if (Input::exists()) {
        if (Token::check(Input::get('token'))) {
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'email' => array('required' => true),
                'password' => array('required' => true)
            ));

            if ($validation->passed()) {
                $user = new User();
                $remember = (Input::get('remember') === 'on') ? true : false;
                $login = $user->login(Input::get('email'), Input::get('password'), $remember);
                if ($login) {
                    if ($user->data()->admin == true) {
                        Redirect::to('Admin.php');
                    } else {
                        Redirect::to('index.php');
                    }
                } else {
                    echo 'Sorry, logging in failed';
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
    <title>Sign in</title>
</head>

<body>

</body>

</html>

</form>
<!-- Sing in  Form -->
<section class="sign-in">
    <div class="container">
        <div class="signin-content">
            <div class="signin-image">
                <figure><img src="../images/signin-image.jpg" alt="sing in image"></figure>
                <a href="signup.php" class="signup-image-link">Create an account</a>
            </div>

            <div class="signin-form">
                <h2 class="form-title">Sign in</h2>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="email"><i class="zmdi zmdi-account material-icons-email"></i></label>
                        <input type="text" name="email" id="email" autocomplete="off" placeholder="Your Name" />
                    </div>
                    <div class="form-group">
                        <label for="your_pass"><i class="zmdi zmdi-lock"></i></label>
                        <input type="password" name="password" id="password" autocomplete="off" placeholder="Password" />
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="remember" id="remember" class="agree-term" />
                        <label for="remember" class="label-agree-term"><span><span></span></span>Remember me</label>
                    </div>
                    <div class="form-group form-button">
                        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                        <input type="submit" value="Log in">
                    </div>
                </form>
                <div class="social-login">
                    <span class="social-label">Or login with</span>
                    <ul class="socials">
                        <li><a href="#"><i class="display-flex-center zmdi zmdi-facebook"></i></a></li>
                        <li><a href="#"><i class="display-flex-center zmdi zmdi-twitter"></i></a></li>
                        <li><a href="#"><i class="display-flex-center zmdi zmdi-google"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>