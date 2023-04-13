<?php 

require_once 'myconfig.php';

$user = new User();
$user->logout();

Redirect::to('index.php');
