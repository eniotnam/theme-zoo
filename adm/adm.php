<?php

session_start();
if($_SESSION['connected'] == false){
    header('location:../index.php');
}
require_once '../models/article.php';
require_once '../models/user.php';

require_once '../models/query.php';


$query = new query;
$nb = new article;
$us = new user;
$pseudo = $_SESSION['pseudo'];
$adminf = $us->getAdmin($_SESSION['id']);



?>
<head>
    <title>Pannel Admin</title>

    <!--
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/style.css">

    <link rel="stylesheet" type="text/css"  href="../css/adm.css">

</head>
<div class="container">
    <div class="row  justify-content-center">

        <nav id="navigation" class="col-10 col-lg-12 justify-align-center">
            <ul class="col-12 col-lg-10 list  ">

                <li id="first" class="col-2 col-lg-2">
                    <a href="javascript:void(0)" onclick="Showarticle();">Invités</a>
                </li>

                <li  class="col-2 col-lg-2">
                    <a href="javascript:void(0)" onclick="Showcategorie();">Animaux</a>
                </li>
                <li  class="col-2 col-lg-4">
                    <a href="javascript:void(0)" onclick="Showhome();" >Home</a>
                </li>
                <li  class="col-2 col-lg-2">
                    <a href="javascript:void(0)" onclick="Showadm();"> <?php echo $_SESSION['pseudo'] ;?></a>
                </li>
                <li class="col-2 col-lg-2" >

                    <a href="../logout.php">Logout</a>
                </li>

            </ul>
        </nav>
    </div>
</div>
<div class="header-spacer"></div>
<div class="container-fluid ">
    <div class="row allarticle justify-content-around">

        <div class="col-10 col-lg-4  case"><label class="col-sm-12 col-md-12" >INVITES</label>

            <h6><?php $query->Counterby('guest','id_animaux is null');echo "/";$query->Counter('guest');?></h6>
            <div class="col-md-12 caser">
                <?php  
                $name=$query->selectWithID('*', 'guest', 'id_animaux is null');
                foreach($name as $invite) {
                ?>
                <div class="col-12 listing"> <p class="col-md-4">- <?php echo $invite['name']."<p class='col-6'>".$invite['lastname']."</p>";?><form action="../models/forms.php" class="col-md-1"> <input type="hidden" name="id" value="<?php echo $invite['id'];?>"><button class="col-12" type="submit" name="suppruser"><i class="fa fa-trash-o" ></i>
                    </button></form>
                </div>

                <?php }?>
            </div>
        </div>
        <div class="col-10 col-lg-4 case"><label class="col-12 " >ANIMAUX</label> <h6><?php $query->Counterby('animals','available is  null');echo "/";$query->Counter('animals');?></h6>
            <div class="col-md-12 caser">
                <?php  
                $name=$query->selectWithID('*', 'animals', 'available is null');
                foreach($name as $invite) {
                    $link=$query->autolink($invite['link']);

                ?>
                <div class="col-md-12 listing"> <p class="col-md-4">- <?php echo $invite['name']."</p><p class='col-md-6'>".$link."</p>";?><form action="../models/forms.php" class="col-md-2"> <input type="hidden" name="id" value="<?php echo $invite['id'];?>"><button class="col-md-12" type="submit" name="suppranimaux"><i class="fa fa-trash-o" ></i>
                    </button></form>
                </div>
                <?php }?>
            </div>
        </div>
        <div class="col-10 col-lg-4  case"><label class="col-sm-12 col-md-12" >INSCRITS</label>

            <h6><?php $query->Counterby('guest','id_animaux is not null');echo "/";$query->Counter('guest');?></h6>
            <div class="col-md-12 caser">
                <?php  
                $name=$query->selectWithID('*', 'guest', 'id_animaux is not null');
                foreach($name as $invite) {
                    $couple = $query->selectWithID("name,lastname",'guest','id ='.$invite['id_couple']);
                    $animaux= $query->selectWithID("name",'animals','id ='.$invite['id_animaux'])
                ?>
                <div class="col-12 listing"> <p class="col-md-12">- <?php echo $invite['name']." ".$couple[0]['name']." ".$couple[0]['lastname']." en ".$animaux[0]['name']. "</p>";
                    
                    ?>
                </div>

                <?php }?>
            </div>
        </div>
    </div>

</div>
<div class="container listarticle" id="listarticle">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 justify-content-center"style="text-align:center;">
            <h2 style="margin:0; border-bottom:1px solid black;">Inviter d'autres personnes</h2>
            <form method="POST" action="../models/forms.php" class="form " id="formart" style="text-align:center;margin:20px;">



                <?php
                if(isset($erreurs))
                {
                    echo '<font color="red">'. $erreurs.'</font>';
                }
                ?>
                <div class="col-12  " >
                    <input type="text" class=" col-3" name="prenom" placeholder="Prenom">
                    <input type="text" class=" col-3" name="nom" placeholder="Nom">
                    <label class="col-10">si en Couple(sinon laisser vide):</label>

                    <input type="text" class=" col-3" name="prenom2"placeholder="Prenom">
                    <input type="text" class=" col-3" name="nom2"placeholder="Nom">
                </div>

                <input type="submit" style="margin:auto;margin-top:20px;" class=" col-2  add" name='addguest'id="add"  value="Ajouter" >

            </form>
        </div>
    </div>


</div>
<div class="container listcategorie" id="listcategorie">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 justify-content-center"  style="text-align:center;">
            <h2 style="margin:0; border-bottom:1px solid black">Ajouter un Animal</h2>
            <form method="POST" action="../models/forms.php"class="form" style="text-align:center;margin:20px;">
                <?php
                if(isset($erreurs))
                {
                    echo '<font color="red">'. $erreurs.'</font>';
                }
                ?>

                <div class="col-12 " >

                    <input type="text" class="col-6"  name="name"  placeholder="Nom de l'animal" class="textbox" required>
                    <input type="text" class="col-6"  name="link"  placeholder="link" class="textbox" required>

                </div>
                <input type="submit"  class="  col-2  add" name="addanimals" value="Ajouter" >

            </form>
        </div>

    </div>
</div>
<div class="container " id="listadm">
    <div class="row justify-content-center"style="display:flex; margin-bottom:20px; margin-top:0;">
        <div class="col-12 col-lg-8 justify-content-center"  style="text-align:center;">
            <h2 style="margin:0; border-bottom:1px solid black">Bienvenue sur ton profil <?php echo ucfirst($_SESSION['pseudo']);?></h2>
            <form method="POST" action="../models/forms.php" class="form" style="text-align:center;margin:20px;">

                <?php if(isset($erreurs))
{
    echo '<font color="red">'.$erreur.'</font>';
}
                ?>

                <div class="col-12 " >
                    <input type="password" autocomplete="off" class="col-6" name="Amdp"  placeholder="Ancien mdp" class="textbox">

                    <input type="password" autocomplete="off" class="col-6" name="Nmdp"  placeholder="Nouveau mdp" class="textbox">
                    <input name="id"  type="hidden" value="<?php echo $_SESSION['id']; ?>" class="textbox">

                </div>
                <input type="submit" style="margin-top:50px;" class="col-2 add" name="modifadmin" value="Modifier" >

            </form>
        </div>
    </div>
</div>

<script type="text/javascript">


    $('#listarticle').hide();
    $('#listcategorie').hide();
    $('#listadm').hide();
    function hideall(){
        $('#listarticle').hide();
        $('#listcategorie').hide();
        $('.allarticle').hide();

        $('#listadm').hide();
    }
    function Showarticle(){
        hideall();
        $('#listarticle').show();

    }
    function Showhome(){
        hideall();
        $('.allarticle').show();

    }
    function Showcategorie(){
        hideall();
        $('#listcategorie').show();

    }
    function Showuser(){
        hideall();
        $('#listuser').show();

    }
    function Showadm(){
        hideall();
        $('#listadm').show();

    }
    function addInput(name,type,holder,id){
        var div = document.getElementById(id);
        if(type == 'textarea'){
            var input = document.createElement("textarea");
        }else{
            var input = document.createElement("input");
            input.type =type;
        }

        input.name = name;

        input.placeholder =holder;
        div.appendChild(input);
    }
    function addbloc(){
        addpic();

    }
    function addpic() {
        addInput("pic[]","file","image","box");
        addtexte();
    }
    function addslider(id) {
        addInput("slider[]","file","photo",id);
    }
    function addtexte() {
        addInput("texte[]","textarea","texte de l'image","box");
    }
</script>