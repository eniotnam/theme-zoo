<?php
session_start();
if($_SESSION['connected'] == false){
    header('location:../index.php');
}
require_once '../models/article.php';
require_once '../models/user.php';
require_once '../models/mail.php';
require_once '../models/query.php';
require_once '../models/vote.php';
require_once '../models/commentaire.php';

$query = new query;
$pseudo = $_SESSION['pseudo'];
$mail = new email;
$nb = new article;
$us = new user;

$categorie =$nb->getcat();
$art =$nb->getcatbyid($_GET['id']);
if(!$art){
    header('location:adm.php');
}

$titre =$art['nom'];
$id =$_GET['id'];
$img = json_decode($art['img']);
$sli = json_decode($art['slider']);

if(isset($_POST['modifslider'])){
    
    if(isset($_FILES['slider']) AND !empty($_FILES['slider']['name'])){
        for($i=0;$i<count($_FILES['slider']['name']);$i++){

            if(empty($_FILES['slider']['name'][$i])){
                $slider[$i]=$sli[$i];
            }
            else{
                $pic = $_FILES['slider']['name'][$i];
                $fil= $_FILES['slider']['type'][$i];
                $tmp= $_FILES['slider']['tmp_name'][$i];
                $mime_valid = ['image/png', 'image/jpeg','image/gif'];
                $extension_valid = ['png', 'jpeg','jpg','gif'];
                $extension = pathinfo($pic)['extension'];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $tmp);


                if(in_array($extension, $extension_valid) && in_array($mime, $mime_valid)){

                    move_uploaded_file($tmp, '../img/'.$pic);
                    $slider[$i]=$_FILES['slider']['name'][$i];
                }

            }
        }
    }
    else{
        $slider= $sli;
    }
    
     if(isset($_FILES['img']) AND !empty($_FILES['img']['name'])){
        for($i=0;$i<count($_FILES['img']['name']);$i++){

            if(empty($_FILES['img']['name'][$i])){
                $imgs[$i]=$sli[$i];
            }
            else{
                $pic = $_FILES['img']['name'][$i];
                $fil= $_FILES['img']['type'][$i];
                $tmp= $_FILES['img']['tmp_name'][$i];
                $mime_valid = ['image/png', 'image/jpeg','image/gif'];
                $extension_valid = ['png', 'jpeg','jpg','gif'];
                $extension = pathinfo($pic)['extension'];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $tmp);


                if(in_array($extension, $extension_valid) && in_array($mime, $mime_valid)){

                    move_uploaded_file($tmp, '../img/'.$pic);
                    $imgs[$i]=$_FILES['img']['name'][$i];
                }
            }
        }
    }
    else{
        $imgs= $img;
    }
    $titres = htmlentities($_POST['titre']);
    $sliderjson = json_encode($slider);
    $imgjson = json_encode($imgs);
    $nb->slideru($imgjson,$sliderjson,$id,$titres);
}



if(isset($_POST['supprslider'])){
    $id =$_GET['id'];
    $id_slider =$_POST['id'];
    $l=0;
    for($i=0;$i<count($sli);$i++){
        if($i != $id_slider ){
            $newsli[$l]=$sli[$i];
            $l++;
        }
    }

    $sliderjson = json_encode($newsli);
    $nb->updateslide($sliderjson,$id);
}


?>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/articlebycat.css">
    <link rel="stylesheet" type="text/css"  href="../css/fonts.css">
    <link rel="stylesheet" type="text/css"  href="../css/adm.css">
    <link rel="stylesheet" type="text/css"  href="../css/article.css">
    <style>
        hr{
           border:1px dashed black;
            color:black;
          
        }
        label:after{
            border-bottom: 1px solid grey;
            content: '';
            display: block;
            margin-left: 0;
            margin-top: 0;
            width: 100%;
        }
        form{
            align-content: center;
            align-items: center;
        }
        button{
            margin:0;
        }
        .back{
            background:white;
            z-index: 100;
            padding:0;
            position:fixed;
           padding:10px;
         
            border-bottom:1px solid black;
            border-radius: 0 0 10px 0;
        }
        .header-space{
            height: 180px;
        }
    </style>
</head>
<a href="adm.php"class="col-md-2 back"><i class="fa fa-arrow-left" aria-hidden="true"></i> Retourner au pannel admin</a>

 <form method="POST" enctype="multipart/form-data" >
<div class="col-md-10 col-md-offset-1">
   

        <input type="submit" style="margin:20px;float:right; background:green;color:white;" class="input-group  col-md-2  add" name="modifslider" value="Modifier" >
        
        <label class="col-md-12">IMAGES CAROUSEL</label>
        <div class="col-md-12" id="slider">
           <button type="button" onclick="addslider('slider')" >+</button>
            <?php 
            if($sli){
                for($i = 0 ; $i < count($sli) ;$i++){

            ?><div class="col-md-3">
            <img width="100%" src="../img/<?php echo $sli[$i]?>">
            <input type="file" class="form-control textbox " name="slider[]" > <form method="POST" class="col-md-12"style="display:flex;margin:0;padding:0;"><input type="hidden" name="id" value="<?php echo $i;?>"><button class="col-md-12" type="submit" name="supprslider">SUPPRIMER IMAGE
            </button></form>
            </div><?php } }else{?> <input type="file" class="form-control textbox " name="slider[]" ><?php }?>
        </div>
          <label class="col-md-12">TITRE</label><input class="form-control textbox"type="texte" name="titre" value="<?php echo $titre; ?>">
          
           <?php if($img){ ?>
            <div class="col-md-4">
                <label>IMAGE ACCUEIL </label>
                <img width="100%" src="../img/<?php echo $img[0]; ?>">
                <input type="file" class="form-control textbox " name="img[]" >
            </div> 
            <div class="col-md-4">
               <label>IMAGE ALLCAT </label>
                <img width="100%" src="../img/<?php echo $img[1]; ?>">
                <input type="file" class="form-control textbox " name="img[]" >
            </div>
            <?php } else { ?>
            <div class="col-md-4">
            <label>IMAGE ACCUEIL </label>
                <input type="file" class="form-control textbox " name="img[]" ></div>
            <div class="col-md-4">
                <label>IMAGE ALLCAT </label><input type="file" class="form-control textbox " name="img[]" ></div>
            <?php } ?>
       
  
</div>

   </form>



<div class="col-md-12 header-space"></div>


<script type="text/javascript">


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