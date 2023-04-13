<?php

require_once 'myconfig.php';

$admin = new User;

    if($admin->data()->admin == true){
$user = DB::getInstance();

if(Input::get('delete_product')){
    $toDelete = Input::get('delete_product');
    $user->delete('products', array('id','=',$toDelete));
    Redirect::to('Admin.php');

}

    }else{
        Redirect::to('index.php');
    }
