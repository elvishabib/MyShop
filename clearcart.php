<?php

require_once 'myconfig.php';

Session::delete('cart');
Redirect::to('index.php');
