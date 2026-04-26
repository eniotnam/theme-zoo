<?php
require_once 'db.php';
class article extends db{
    
    public function getDb(){
        return Db::getInstance();
    }
 
    
    public function addarticle($titre,$nom,$lien,$descri,$date,$img,$fil,$fils){
         $dbh = $this->getDb();

        if (!empty($_FILES)) {

            $mime_valid = ['image/png', 'image/jpeg','image/gif'];
            $extension_valid = ['png', 'jpeg','jpg','gif'];
            $extension = pathinfo($img)['extension'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $fil['tmp_name']);

            $mime_valids = ['application/pdf'];
            $extension_valids = ['pdf'];
            $extensions = pathinfo($lien)['extension'];
            $finfos = finfo_open(FILEINFO_MIME_TYPE);
            $mimes = finfo_file($finfos, $fils['tmp_name']);


            $reqadmin = $dbh->prepare("SELECT * FROM users WHERE pseudo = ? AND rank = 1 ");
            $reqadmin->execute(array($nom));
            $adminexist= $reqadmin->rowCount();
            if($adminexist == 1)
            {


                $reqtitre = $dbh->prepare("SELECT * FROM article WHERE titre = ? ");
                $reqtitre->execute(array($titre));
                $titreexist= $reqtitre->rowCount();
                if($titreexist == 0)
                {
                    if(in_array($extension, $extension_valid) && in_array($mime, $mime_valid)){

                        move_uploaded_file($fil['tmp_name'], '../img/' . $img);

                        if(in_array($extensions, $extension_valids) && in_array($mimes, $mime_valids)){

                            move_uploaded_file($fils['tmp_name'], '../article/' . $lien);

                            $insertmbr =$dbh->prepare("INSERT INTO article (titre, lien, editedby, descri, date, img) VALUES (?, ?, ?, ?, ?, ? ) ");
                            $insertmbr -> execute(array($titre, $lien, $nom, $descri, $date, $img));
                        }else{
                            $erreurs = 'Extension Error';
                        }
                    }
                    else{
                        $erreurs = 'Extension Error';
                    }
                }
                else $erreurs = "titre deja existant";
            }

            else
            {
                $erreurs = "vous n'êtes pas admin";
            }
        }
    }

    public function supprarticle($id){
         $dbh = $this->getDb();
        if( !empty($id) )
        {
            $requser = $dbh->prepare("SELECT * FROM article WHERE id = ?  ");
            $requser->execute(array($id));


            $userexist = $requser->rowCount();
            if($userexist == 1)
            {
                $req = $dbh->prepare('DELETE FROM article WHERE id = :id ');
                $req->execute([
                    ':id' => $id]);


            }
            else
            {
                $erreur = "Mauvais mail ou mot de passe incorrect !";
            }
        }
    }

    public function countArticle(){
         $dbh = $this->getDb();

        $stmt = $dbh->prepare('SELECT COUNT(*) as somme FROM article ');
        $stmt->execute();

        while ($result = $stmt->fetch()){
            echo $result[0];
        }
    }

    public function getArticle(){

        $dbh = $this->getDb();

        $stmt = $dbh->prepare('SELECT * FROM article ');
        $stmt->execute();

        while ($result = $stmt->fetch()){
            $id = $result['id'];
            $titre = $result['titre'];

            $desc = $result['descri'];

            $img = $result['img'];

            $lien = $result['lien'];

            $datee =  "poster le ".$result['date'];

            $editedby = $result['editedby'];

            echo "
            <div class='col-md-3 col-md-offset-1 'style='padding-bottom:40px;'>
            <div class='col-md-12  thumbnail article' >
             <a href='../article/$lien' target='_blank'>
                        <div class='caption'>
                            <h4>$titre</h4>
                            <p>$desc</p>
                            <p>$editedby <br> $datee</p>

                        </div>
                        </a>
                        <img src='../img/$img' alt='...'></a>

                    </div>
                    <form>
                     <input class='id' name='id' value='$id '>
                    <input type='submit' class='col-md-12  del' href='#delarticle'  name='supprarticle' value='Supprimer' style='margin-top:-20px;'>
                    </form></div>
                     ";


        }


    }

    public function delarticle($id){
          $dbh = $this->getDb();
        $req = $dbh->prepare('DELETE FROM article WHERE id = :id ');
        $req->execute([
            ':id' => $id]);
    }

    public function getLastNews(){
        $dbh = $this->getDb();

        $stmt = $dbh->prepare('SELECT * FROM article order by id DESC;');
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;

    }
    

    public function getBuzzNews(){
  $dbh = $this->getDb();

        $stmt = $dbh->prepare('SELECT * FROM article DESC;'); //todo
        $stmt->execute();

        while ($result = $stmt->fetch()){
            $id = $result['id'];

            $titre = $result['titre'];

            //                $desc = $result['descri'];

            //                $contenu = $result['contenu'];

            $userid = $result['lien'];

            //                $datee = $result['datee'];

            $editedby = $result['editedby'];
        }
    }

    public function getMostVisitedNews(){
  $dbh = $this->getDb();

        $stmt = $dbh->prepare('SELECT * FROM article ORDER BY id DESC;');
        $stmt->execute();

        while ($result = $stmt->fetch()){
            $id = $result['id'];

            $titre = $result['titre'];

            $desc = $result['descri'];

            $contenu = $result['contenu'];

            $userid = $result['lien'];

            $datee = $result['datee'];

            $editedby = $result['editedby'];
        }
    }
    public function getNewsLastWeek(){
  
        $date = date('d/m/Y');
        $datej = date('d');
        $datem = date('m');
        $datey = date('Y');
        $slash = '/';

        if($datej < 7){
            $datejj = $datej;
            $datemm = $datem -1;
            while($datej != 1){
                $datejj = $datej -1;
            }
            $datefinal = $datejj + $slash + $datemm + $slash + $datey;
        }else{
            $datejj = $datej - 7;
            $datefinal = $datejj + $slash + $datem + $slash + $datey;
        }

         $dbh = $this->getDb();
        $stmt = $dbh->prepare('SELECT * FROM article WHERE datee BETWEEN :datee AND :dateee LIMIT 3;');
        $arg=[
            ':datee' => $date,
            ':dateee' => $datefinal
        ];
        $stmt->execute($arg);

        while ($result = $stmt->fetch()){
            $id = $result['id'];

            $titre = $result['titre'];

            $desc = $result['descri'];

            $contenu = $result['contenu'];

            $userid = $result['lien'];

            $datee = $result['datee'];

            $editedby = $result['editedby'];

            echo"<div class='item'>
            <div class='row'>
                <div class='col-sm-3 text-center'>
                <img class='img-circle' src='#' href='article.php?id=$id'style='width: 100px;height:100px;'>
                </div>
                <div class='col-sm-9'>
                <p>$titre</p>
                <p>$desc</p>
                <small>$editedby</small>
                </div>
                </div>
            </div>";
        }
    }
}
?>