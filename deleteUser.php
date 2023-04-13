<?php

require_once 'myconfig.php';
$admin = new User;

    if($admin->data()->admin == true){
$user = DB::getInstance();

if(Input::get('delete_id')){
    $toDelete = Input::get('delete_id');
    $user->delete('users', array('id','=',$toDelete));
    Redirect::to('showUsers.php');

}

    }else{
        Redirect::to('index.php');
    }
