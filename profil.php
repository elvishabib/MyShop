<?php 
require_once 'myconfig.php';


$user = new User;
if($user->isLoggedIn()){
    $name =$user->data()->username;


$targetDir = "../images/profiles/";
$fileName = basename($_FILES["file"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

if(isset($_POST["submit"]) && !empty($_FILES["file"]["name"])){
    $allowTypes = array('jpg','png','jpeg','gif','pdf');
    if(in_array($fileType, $allowTypes)){
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
        }
    }
}

$imageUrl = '../images/profiles/' . $user->data()->image;

if(!empty($_FILES['file']['name'])){
        $profile = DB::getInstance()->update('users',$user->data()->id,array(
            'image' => $fileName
        ));
        Redirect::to('profil.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <title>Profil</title>
</head>
<body>



<br><br>
<div class="container-fluid well ">
	<div class="row-fluid">
        <div class="span4" >
		    <img src="<?php echo $imageUrl ?>" class="img-circle">
        </div>
        
        <div class="span4">
            <h3><?php echo $name ;?></h3>
            <h6>Email: <?php echo $user->data()->email ;?></h6>
            <h6>Adress: No informations about yet!.</h6>
            <h6>Old: No informations about yet!.</h6>
            <form action ="" method ="POST" enctype="multipart/form-data">
            <div class="form-group">
            <input type="file" name="file" >
            <button type ="submit" name ="submit" class="btn btn-primary" >Upload Picture</button>
            </div>
            </form>
        </div>
        
        <div class="span2">
            <div class="btn-group">
                <a class="btn dropdown-toggle btn-info" data-toggle="dropdown" href="#">
                    More... 
                    <span class="icon-cog icon-white"></span><span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="#"><span class="icon-wrench"></span> Modify</a></li>
                    <li><a href="logout.php"><span class="icon-user"></span> Log out</a></li>
                    <li><a href="index.php"><span class="icon-home"></span> Home</a></li>
                </ul>
                
            </div>
        </div>
</div>
</div>
</body>
</html>
<?php }else{
    Redirect::to('signin.php');
}