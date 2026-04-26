<?php
session_start();
require_once('models/user.php');

if (!empty($_SESSION['connected'])){

    $iduser = $_SESSION['id'];

    $user = new user;
    $user->getUserInfo($iduser);
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Vox Kratos</title>
        <meta charset="utf-8">

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<!--       <link rel="stylesheet" href="./css/style.css">-->
        <style>
            body{
                 background-color: #e9ebee;
            }
            
            .navbar{
                  background:rgba(3, 65, 0, 0.54);
            }
            ul.dropdown-menu {
    background: #6d8f6d;
}
            li h4{
                margin-top:17px;
            }
            h1 {
                text-align: center;
                font-family: Tahoma, Arial, sans-serif;
                color: black;
                margin: 80px 0;
            }

            .logo2{
                margin-top:-10px;
                margin-right:0px;
                margin-left:70px;
                height:40px;
                width:40px;
            }

            .logoo {
                margin-right:60px;

            }
            .msg,.notif{
                margin-left:5px;
                margin-top:5px;
                position:absolute;
                z-index:200;
                height:250px;
                width:170px;
                background:grey;
            }
            .drop{
                margin-top:8px;
                margin-bottom:8px;
            }
        </style>
    </head>
    <body>

        <nav class="navbar navbar-default ">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="home.php"><img class="logo2" src="../logo/logo4.png" alt="" /></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <?php 
                    const PAGES_SANS_NAV = [

                        '/VoxKratos/profil.php',
                    ];

                    if (!in_array($_SERVER['SCRIPT_NAME'], PAGES_SANS_NAV)) {
                        // afficher la barre

                    ?>

                    <form class="navbar-form navbar-left" >
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search">
                        </div>
                        <button type="submit" class="btn btn-default glyphicon glyphicon-search"></button>
                    </form>
                    <?php
                    }
                    ?>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                        <div class="dropdown navbar-right ">

                            <ul class="dropdown nav navbar-nav navbar-right" id="logoo"> 
                                <?php
                                if (isset($_SESSION['connected'])){
                                  
                                ?>
                                <li> <?php $user->name( $_SESSION['id']); ?></li>
                                <li><a href="profil.php?id=<?php echo $id; ?>" class="glyphicon glyphicon-user"></a></li>
<!-- PROCHAINE MAJ
                                <li><a href="#" class="glyphicon glyphicon-envelope"></a></li>
                                <li><a href="#"class="glyphicon glyphicon-globe"></a></li> 
-->
                                <?php 
                                }
                                ?>
                                <button class="btn btn-default dropdown-toggle drop" type="button" id="dropdownMenu1" data-toggle="dropdown">

                                    <span class="caret"></span>
                                </button>


                                <ul class="dropdown-menu menu" role="menu" aria-labelledby="dropdownMenu1">

                                    <?php 
                                    if (isset($_SESSION['connected'])){
                                 


    if (!in_array($_SERVER['SCRIPT_NAME'], PAGES_SANS_NAV)) {
        // afficher la barre

                                    ?>
                                    <li><a href="#">Modifier le profil</a></li><?php }?>
                                    <li><a href="#">Paramètres</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#">À Propos</a></li>
                                    <li class="divider"></li>
                                    <li role="presentation"><a href="logout.php"><span>Logout</span></a></li>
                                    <?php 
                                    }
                                    else{
                                    ?>
                                    <li class ="log"role="presentation"><a href="index.php" ><span>Login</span></a></li><?php
                                    }
                                    ?>
                                </ul>
                                <div class="msg">

                                </div>
                                <div class="notif">

                                </div>
                            </ul>





                        </div>
                    </div>
                    <!-- /.navbar-collapse -->
                </div>
            </div>
        </nav>

        <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script type="text/javascript">
            $('.msg').hide();
            $('.notif').hide();
            $('.glyphicon-envelope').click(function(){
                $('.msg').slideToggle(1000);
            });
            $('.glyphicon-globe').click(function(){
                $('.notif').slideToggle(1000);
            });
        </script>
    </body>
</html>
