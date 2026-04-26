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
$art =$nb->getArticlebyid($_GET['id']);
if(!$art){
    header('location:adm.php');
}
$text =json_decode($art['texte']);
$titre =$art['titre'];
$auteur =$art['auteur'];
$adminf = $us->getAdminmore($auteur);
$date =$art['date'];
$id =$_GET['id'];
$message =$art['mesperso'];
$descri =$art['metadescription'];
$img = json_decode($art['img']);
$sli = json_decode($art['slider']);
$cat = json_decode($art['categorie']);
$photoilust = json_decode($art['photoillustre']);

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
    $date =  date('l j , F');
    $id=$_GET['id'];
    $sliderjson = json_encode($slider);
    $nb->sliderupdate($sliderjson,$date,$id);
}
if(isset($_POST['modifauteur'])){

    if(isset($_FILES['prespic']) AND !empty($_FILES['prespic']['name'])){

        for($i=0;$i<count($_FILES['prespic']['name']);$i++){

            if(empty($_FILES['prespic']['name'][$i])){
                $prespic[$i]=$photoilust[$i];

            }
            else{
                $pic = $_FILES['prespic']['name'][$i];
                $fil= $_FILES['prespic']['type'][$i];
                $tmp= $_FILES['prespic']['tmp_name'][$i];
                $mime_valid = ['image/png', 'image/jpeg','image/gif'];
                $extension_valid = ['png', 'jpeg','jpg','gif'];
                $extension = pathinfo($pic)['extension'];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $tmp);


                if(in_array($extension, $extension_valid) && in_array($mime, $mime_valid)){

                    move_uploaded_file($tmp, '../img/'.$pic);
                    $prespic[$i]=$_FILES['prespic']['name'][$i];
                }

            }

        }

    }
    else{
        $prespic=$photoilust;
    }


    $prespicjson=json_encode($prespic);
    $id=$_GET['id'];
    $auteur = htmlentities($_POST['auteur']);
    if($message == $_POST['mess']){
        $message = $_POST['mess'];
    }else{
        $message = nl2br(htmlentities($_POST['mess']));}
    if($meta == $_POST['descri']){
        $meta = $_POST['descri'];
    }else{
        $meta= nl2br(htmlentities($_POST['descri']));}

    $date =  date('l j , F');
    $nb->modifauteur($prespicjson,$auteur,$message,$meta,$date,$id);
}

if(isset($_POST['modifart'])){

    if(isset($_FILES['pic']) AND !empty($_FILES['pic']['name'])){

        for($i=0;$i<count($_FILES['pic']['name']);$i++){

            if(empty($_FILES['pic']['name'][$i])){
                $imgs[$i]=$img[$i];
            }
            else{
                $pic = $_FILES['pic']['name'][$i];
                $fil= $_FILES['pic']['type'][$i];
                $tmp= $_FILES['pic']['tmp_name'][$i];
                $mime_valid = ['image/png', 'image/jpeg','image/gif'];
                $extension_valid = ['png', 'jpeg','jpg','gif'];
                $extension = pathinfo($pic)['extension'];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $tmp);


                if(in_array($extension, $extension_valid) && in_array($mime, $mime_valid)){

                    move_uploaded_file($tmp, '../img/'.$pic);
                    $imgs[$i]=$_FILES['pic']['name'][$i];
                }

            }

        }

    }
    else{
        $imgs=$img;
    }



    for($k=0;$k<count($_POST['texte']);$k++ ){
        if($text[$k] == $_POST['texte'][$k]){
            $textes[$k] = $_POST['texte'][$k];

        }else{
            $teste =  str_replace( "<br/>","", $_POST['texte'][$k] );
            $textes[$k] = nl2br(htmlentities($teste));}


    }

    $categorie=$_POST['categorie'];
    $id=$_GET['id'];
    $textejson = json_encode($textes);
    $categoriejson = json_encode($categorie);
    $date =  date('l j , F');
    $titre = htmlentities($_POST['titre']);;
    $imgjson = json_encode($imgs);


    $nb->modart($textejson,$imgjson,$categoriejson,$date,$titre,$id);

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
    $nb->updateslider($sliderjson,$id);
}
if(isset($_POST['supprimage'])){
    $id =$_GET['id'];
    $id_slider =$_POST['id'];
    $l=0;
    $newtext[0]=$text[0];
    for($i=0;$i<count($img);$i++){
        if($img[$i] != $img[$id_slider] ){
            $newimg[$l]=$img[$i];
            $newtext[$l+1]=$text[$i+1];
            $l++;
        }
    }
    $textejson = json_encode($newtext);
    $imgjson = json_encode($newimg);
    $nb->updateimg( $textejson,$imgjson,$id);
}
function autolink($string)
{
    $content =explode(' ',$string);
    $sentence ="";
    foreach($content as $content1){
        if(preg_match("@(https://[^ ]+)@",$content1,$f)){

            $nom = preg_split('/[.]+/i',$f[0]);
            //        $nom= parse_url($til, PHP_URL_HOST);
            //        echo $f;
            $sentence.= preg_replace("@(https://[^ ]+)@", " <a class='lien' href=\"$1\" target='_blank'>$nom[1]</a>", $content1);
        }
        else{
            $sentence.=" ".$content1;
        }
    }
    return $sentence;
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


<div class="col-md-10 col-md-offset-1">
    <form method="POST" enctype="multipart/form-data" >


        <input type="submit" style="margin:20px;float:right; background:green;color:white;" class="input-group  col-md-2  add" name="modifslider" value="Modifier" >
        <label class="col-md-12">IMAGES CAROUSEL</label>
        <div class="col-md-12" id="slider"><button type="button" onclick="addslider('slider')" >+</button>
            <?php 
            if($sli){
                for($i = 0 ; $i < count($sli) ;$i++){

            ?><div class="col-md-3">
            <img width="100%" src="../img/<?php echo $sli[$i]?>">
            <input type="file" class="form-control textbox " name="slider[]" > <form method="POST" class="col-md-12"style="display:flex;margin:0;padding:0;"><input type="hidden" name="id" value="<?php echo $i;?>"><button class="col-md-12" type="submit" name="supprslider">SUPPRIMER IMAGE
            </button></form>
            </div><?php } }else{?> <input type="file" class="form-control textbox " name="slider[]" ><?php }?>
        </div>
    </form>
</div>

<div class="col-xs-12  col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 articles">
    <hr class="col-md-12">  
    <div class="col-xs-10 col-sm-10 col-md-10 title">
        <form method="POST" enctype="multipart/form-data" >


            <input type="submit" style="margin:20px;float:right; background:green;color:white;" class="input-group  col-md-2  add" name="modifart" value="Modifier" >
            <label class="col-md-12">TITRE</label><input class="form-control textbox"type="texte"name="titre" value="<?php echo $titre; ?>">
            <h5>
                <span><?php echo $date; ?></span>
                <?php $c=0;
                for($j = 0;$j<count($categorie);$j++){ 
                    $nom=$categorie[$j]['nom'];
                    foreach($cat as $cate){
                        if($cate == $nom){echo " <span><input type='checkbox' value='$nom' name='categorie[]' checked>$nom</span>";$c=1;}
                    }
                    if ($c==0){echo " <span><input type='checkbox' value='$nom' name='categorie[]' >$nom</span>";$c=0;}else{$c=0;}
                } 
                ?>

            </h5>
            <label class="col-md-12">TEXTE ARTICLE</label>
            <textarea class="form-control textarea" name="texte[]" ><?php echo str_replace( "<br />","", $text[0] ); ?>

            </textarea>

            <div class="col-xs-12 col-sm-12 col-md-12 text"id="box">
                <label class="col-md-12">IMAGE/TEXTE</label>
                <button type="button" onclick="addbloc()" >+</button>
                <?php  if($img){for($p=0;$p<count($img);$p++){?>
                <div class="col-xs-12 col-sm-12 col-md-12 artidesc">
                    <img style="margin:auto;"width="50%" src="../img/<?php echo $img[$p]; ?>">
                    <input type="file" class="form-control textbox " name="pic[]" >
                    <textarea class="form-control textarea" name="texte[]" ><?php echo str_replace( "<br />","", $text[$p+1]);?></textarea>
                    <form method="POST" class="col-md-12"style="display:flex;margin:0;padding:0;"><input type="hidden" name="id" value="<?php echo $i;?>"><button class="col-md-12" type="submit" name="supprimage">SUPPRIMER CE BLOCK
                        </button></form>

                </div>
                <?php }}else{?> <input type="file" class="form-control textbox " name="pic[]" >
                <textarea class="form-control textarea" name="texte[]" ></textarea><?php }?>
            </div>

        </form>
        <hr class="col-md-12">
        <div class="col-xs-12 col-sm-12 col-md-12 auteurs">
            <form method="POST" enctype="multipart/form-data" >


                <input type="submit" style="margin:20px;float:right; background:green;color:white;" class="input-group  col-md-2  add" name="modifauteur" value="Modifier" >
                <?php if($photoilust){ ?>
                <div class="col-md-4">
                    <label>Petite image </label>
                    <img width="100%" src="../img/<?php echo $photoilust[0]; ?>">
                    <input type="file" class="form-control textbox " name="prespic[]" >
                </div> 
                <div class="col-md-4">
                    <label>grande image </label>
                    <img width="100%" src="../img/<?php echo $photoilust[1]; ?>">
                    <input type="file" class="form-control textbox " name="prespic[]" >
                </div>
                <?php } else { ?>
                <div class="col-md-4">
                    <label>Petite image </label>
                    <input type="file" class="form-control textbox " name="prespic[]" ></div>
                <div class="col-md-4">
                    <label>grande image </label><input type="file" class="form-control textbox " name="prespic[]" ></div>
                <?php } ?>
                <label class="col-md-12">META-DESCRIPTION</label>
                <p><textarea class="form-control textarea" name="descri"><?php echo $descri; ?></textarea></p>
                <label class="col-md-12">COMMENTAIRE AUTEUR</label>
                <p><textarea class="form-control textarea" name="mess"><?php echo $message; ?></textarea></p>

                <label class="col-md-12">CHOIX AUTEUR</label>
                <div class="col-xs-12 col-sm-12 col-md-12 auteur">

                    <select class="col-md-12"   name="auteur">
                        <option value="camille"><?php echo ucfirst($auteur); ?></option>
                        <option value="camille">Camille</option>
                        <option value="juliette">Juliette</option>
                        <option value="C&J">C&J</option>
                    </select>
                </div> 
            </form>

        </div>

    </div>


</div>




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