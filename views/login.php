<?php
session_start();
require_once 'models/query.php';
$query = new query;


if(isset($_GET['formconnexion']))
{
    $mailconnect = $_GET['pseudoconnect'];
    $mdpconnect = $_GET['mdpconnect'];
    if(!empty($mailconnect) && !empty($mdpconnect) )
    {
        $pseudoconnect = htmlspecialchars($_GET['pseudoconnect']);
        $mdpconnect = sha1($_GET['mdpconnect']);

       $dbh = $query->getDb();
    
     $requser = $dbh->prepare("SELECT * FROM users WHERE pseudo = ? AND mdpcrypt = ? ");
        $requser->execute(array($pseudoconnect, $mdpconnect));


        $userexist = $requser->rowCount();
        if($userexist == 1)
        {
            $userinfo = $requser->fetchALL();
            $_SESSION['id'] = $userinfo[0]['id'];
            $_SESSION['pseudo'] = $userinfo['pseudo'];
            $_SESSION['mail'] = $userinfo['mail'];
            $_SESSION['connected'] = true;
            $_SESSION['rank'] = $userinfo[0]['rank'];
           
            if($_SESSION['rank'] == 1){
                header('location: adm/index.php');
            }
            else    {
                header("Location:home.php");}
        }
        else
        {
           $erreur = "Mauvais mail ou mot de passe incorrect !";
            
        }
    }
    else
    {
        $erreur = "Tous les champs doivent être complétés !";
    }
}

if (isset($_GET['forminscription']))
{ 
    $pseudo = htmlspecialchars($_GET['pseudo']);

 $mdp = sha1($_GET['mdp']);
 $mdp2 = sha1($_GET['mdp2']);

 if(!empty($_GET['pseudo']) 
    AND !empty($_GET['mdp']) && !empty($_GET['mdp2']))
 {

    
             if(strlen($pseudo)>=3)
             {

                 $reqmail = $dbh->prepare("SELECT * FROM users WHERE pseudo = ? ");
                 $reqmail->execute(array($pseudo));
                 $pseudoexist= $reqmail->rowCount();
                 if($pseudoexist == 0)
                 {
                     if($mdp==$mdp2)
                     {
                         if(strlen($_GET['mdp'])>=8)
                         {
                             $rank = 0;
                             $insertmbr =$dbh->prepare("INSERT INTO users (pseudo, mdpcrypt,rank) VALUES (?, ?,?) ");
                             $insertmbr -> execute(array($pseudo, $mdp,$rank));
                             $ok = "Votre compte a été bien crée ! veuillez vous connecter";
                         }
                         else $erreurs = "mot de passe doit être composé de minimum 8 caractère";
                     }
                     else
                     {
                         $erreurs = "vos mot de passe ne sont pas identiques !";
                     }
                 }
                 else $erreurs ="pseudo déja pris";
             }
             else $erreurs ="pseudo doit être composé de minimum 3 caractères";
        
   
    

 }

}




?>
<html>

    <head>
        <title>Vox Kratos </title>
        <meta charset="utf-8">
        <meta name="Description" CONTENT="Réseau social politique  grâce au quel vous pouvez prendre part à la politique de votre pays">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta property="og:title" content="Vox Kratos" />
        <meta property="og:description" content="Le réseau social politique" />
        <meta property="og:url" content="voxkratos.esy.es" />
        <meta property="og:image" content="http://ia.media-imdb.com/images/rock.jpg" />
        <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <script type="text/javascript" src="http://netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css"
              rel="stylesheet" type="text/css">
        <link href="http://pingendo.github.io/pingendo-bootstrap/themes/default/bootstrap.css"
              rel="stylesheet" type="text/css">
        <style>

            hr{
                height:1px;
                background:#048696;
            }
            .login{

                text-align: center;
            }
            .login h2{
                margin-top:0;
            }
            .register h2{
                margin-top:0;
            }
            .register{
                text-align: center;
            }

            .span{
                width:40px;
            }
            .box{
                margin:auto;
            }
            h1.text-center.text-primary {
                color: darkslategray;
            }
        </style>
    </head>

    <body>
        <html>

            <body>
                <div id="home" class="cover">
                    <div class="background-image-fixed cover-image" style="background-image : url('http://pingendo.github.io/pingendo-bootstrap/assets/blurry/800x600/14.jpg')"></div>
                    <div class="container">
                        <div class="row home">
                            <div class="col-md-5 text-center border">
                                <br>
                                <br>
                                <br>
                                <br>
                                <h1 class="text-inverse"><br>Vox Kratos</h1>
                                <p class="text-inverse">Le réseau social politique</p>
                                <br>
                                <br>
                                <a href="#plus" class="js-scrollTo btn btn-info btn-sm">En savoir plus</a>

                            </div>

                            <div class="container ">    
                                <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-1 col-sm-8 col-sm-offset-2">                    
                                    <div class="panel panel-info" >
                                        <div class="panel-heading">
                                           
                                            <div style="float:right; font-size: 80%; position: relative; top:-10px; color#808080"><a href="#">Mot de passe oublié?</a></div>
                                        </div>     

                                        <div style="padding-top:30px; background-color:#e9ebee" class="panel-body" >

                                            <?php
                                            if(isset($ok))
                                            {
                                                echo '<font color="green">'.$ok.'</font>';
                                            }
                                            ?>



                                            <form method="" action="" class="login">
                                                <h2 style="color:#048696;">Connexion</h2>
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
                                            <hr>
                                            <form method="" action="" class="register">
                                                <h2 style="color:#048696;">Inscription</h2>
                                                <br/>
                                                <?php
                                                if(isset($erreurs))
                                                {
                                                    echo '<font color="red">'. $erreurs.'</font>';
                                                }
                                                ?>
                                                <div  style="margin-bottom: 10px" class="input-group box">
                                                    <span class="input-group-addon span"><i class="fa fa-user"></i></span>
                                                    <input type="text" class="form-control"  name="pseudo" id="pseudo" placeholder="Pseudo" class="textbox" required>
                                                </div>

                                               

                                                <div style="margin-bottom: 10px" class="input-group box">
                                                    <span class="input-group-addon span"><i class="fa fa-key"></i></span>
                                                    <input type="password" class="form-control" name="mdp" id="mdp" placeholder="Mot de passe" class="textbox">
                                                </div>

                                                <div  style="margin-bottom: 10px" class="input-group box">
                                                    <span for="mdp2" class="input-group-addon span"><i class="fa fa-key"></i><i class="fa fa-key"></i></span>
                                                    <input type="password" class="form-control" name="mdp2" id="mdp2" placeholder="Mot de passe" class="textbox">
                                                </div>

                                                <input type="submit" value="Inscription" name="forminscription"> 
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="plus" class="section">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="img/ilustratio.png"
                                     class="img-responsive">
                            </div>
                            <div class="col-md-6">
                                <h1 class="text-primary">Toute l'information législative</h1>
                                <h3>Créez votre propre avis</h3>
                                <p>VoxKratos vous propose de lire les lois présentées ,votées ,oubliées et même celles qui restent à l'état de projet. Des videos débrief seront disponibles pour vous tenir au courant le plus briévement et impartialement possible.L'accessibilitée aux textes étant simplifié pas de place pour les "fakes news" ,les faux debats inutiles télévisés ou radiodiffusés  . Allons à l'essentiel, le contenu des lois.
                                Ce dernier vous sera décrypté par nos équipes mais les textes de loi avec le vocabulaire juridique resteront disponibles pour montrer la neutralité de nos "traductions".<br><br>
                                VoxKratos le site qui vous présente l'actualité législative.</p>
                                 <a href="home.php" class="col-md-4 col-md-offset-4 btn btn-info btn-sm" style="padding:10px;margin-top:40px;font-weight:bold;">SE TENIR AU COURANT</a>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="section">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <h1 class="text-primary">Communiquez et votez</h1>
                                <h3>Ayez un rôle</h3>
                                <p>On vous permet de donner votre avis  sur les textes ,de proposer des améliorations ,de lire les avis des autres et d'y réagir (avec un respect démocratique nous y veillerons).Ne restez pas simple spectateur parlez mais votez aussi. Comme au parlement votez pour ,contre ou neutre ,tout vote sera pris en compte. Chaque semaine nos équipes essaierons de créer des groupes de débat sur des problèmatiques actuelles ou oubliées afin que tout sujet soit traité.<br><br>Voxkratos le site qui vous redonne du pouvoir.</p>
                                 <a href="#home" class="col-md-4 js-scrollTo col-md-offset-4 btn btn-info btn-sm" style="padding:10px;margin-top:40px;font-weight:bold;">AVOIR DU POUVOIR</a>

                            </div>
                            <div class="col-md-6 ">
                                <img src="img/ilustra.png"
                                     class="img-responsive">
                            </div>
                        </div>
                    </div>
                </div>
              
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
                <script>
                    $(document).ready(function() {
                        $('.js-scrollTo').on('click', function() { // Au clic sur un élément
                            var page = $(this).attr('href'); // Page cible
                            var speed = 750; // Durée de l'animation (en ms)
                            $('html, body').animate( { scrollTop: $(page).offset().top }, speed ); // Go
                            return false;
                        });
                    });
                </script>
              
            </body>

        À</html>