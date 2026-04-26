<?php 
session_start();
require_once '../models/query.php';
$query = new query;
if(isset($_GET['formconnexion']))
{
    $pseudo = $_GET['pseudoconnect'];
    $mdpconnect = $_GET['mdpconnect'];
    if(!empty($pseudo) && !empty($mdpconnect) )
    {

        $pseudoconnect = htmlspecialchars($_GET['pseudoconnect']);
        $mdpconnect = sha1($_GET['mdpconnect']);

        $dbh = $query->getDb();

        $requser = $dbh->prepare("SELECT * FROM adm WHERE name = ? AND mdp = ? ");

        $requser->execute(array($pseudoconnect, $mdpconnect));


        $userexist = $requser->rowCount();
        if($userexist == 1)
        {
            $userinfo = $requser->fetchALL();
            $_SESSION['id'] = $userinfo[0]['id'];
            $_SESSION['pseudo'] = $userinfo[0]['name'];

            $_SESSION['connected'] = true;


            header("Location:adm.php");
        }
        else
        {
           
            $erreur = "Mauvais mail ou mot de passe";

        }
    }
    else
    {
        $erreur = "Tous les champs doivent être complétés !";
    }
}

?>
<head>
   
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../css/fonts.css">
    <style>

        form{
            margin-top:10%;
            border:1px solid black;
            border-radius:10px;
            padding:10px;
            text-align: center;
        }

    </style>
</head>
<div class="container">
<div class="row justify-content-center ">
    <form method="" action="" class="col-md-4 col-sm-8 align-self-center">
        <h2 style="margin:0;">Identifiants admin</h2>
        <br/>
        <?php
        if(isset($erreur))
        {
            echo '<font color="red">'.$erreur.'</font>';
        }
        ?>
        <div style="margin-bottom: 10px" class="input-group box">
            <span class="input-group-addon span"><i class="fa fa-user"></i></span>
            <input type="pseudo" class="form-control" name="pseudoconnect" placeholder="Pseudo" size=30 required/>
        </div>

        <div style="margin-bottom: 10px" class="input-group box">
            <span class="input-group-addon span"><i class="fa fa-key"></i></span>
            <input type="password" class="form-control" name="mdpconnect" placeholder="Mot de passe" size=30 required/>
        </div>


        <input type="submit" name="formconnexion" value="Connexion" /> 
    </form>
</div>
</div>

