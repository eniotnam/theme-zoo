<?php
require_once 'db.php';
class groupe extends db{
 public function getDb(){
      return Db::getInstance();
    }
    
    public function addgroupe($titre,$nom,$dispo,$descri,$date,$img,$fil){
        $dbh = $this->getDb();

        if (!empty($_FILES)) {

            $mime_valid = ['image/png', 'image/jpeg','image/gif'];
            $extension_valid = ['png', 'jpeg','jpg','gif'];
            $extension = pathinfo($img)['extension'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $fil['tmp_name']);


            $reqadmin = $dbh->prepare("SELECT * FROM users WHERE id = ? AND rank = 1 ");
            $reqadmin->execute(array($nom));
            $adminexist= $reqadmin->rowCount();
            if($adminexist == 1)
            {


                $reqtitre = $dbh->prepare("SELECT * FROM groupe WHERE nom = ? ");
                $reqtitre->execute(array($titre));
                $titreexist= $reqtitre->rowCount();
                if($titreexist == 0)
                {

                    if(in_array($extension, $extension_valid) && in_array($mime, $mime_valid)){
                        move_uploaded_file($fil['tmp_name'], '../img/' . $img);

                        $insertmbr =$dbh->prepare("INSERT INTO groupe(nom, dispo, id_creator, description, date, img) VALUES (?, ?, ?, ?, ?, ? ) ");
                        $insertmbr -> execute(array($titre, $dispo, $nom, $descri, $date, $img));
                        header('location:index.php#groupe');
                    }else{
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


    public function supprgroupe($id){
       $dbh = $this->getDb();
        if( !empty($id) )
        {
            $requser = $dbh->prepare("SELECT * FROM groupe WHERE id = ?  ");
            $requser->execute(array($id));


            $userexist = $requser->rowCount();
            if($userexist == 1)
            {
                $req = $dbh->prepare('DELETE FROM groupe WHERE id = :id ');
                $req->execute([
                    ':id' => $id]);

                header('location:index.php#groupe');
            }
            else
            {
                $erreur = "Mauvais mail ou mot de passe incorrect !";
            }
        }
    }

    public function countGroupe(){
       $dbh = $this->getDb();

        $stmt = $dbh->prepare('SELECT COUNT(*) as somme FROM groupe');
        $stmt->execute();

        while ($result = $stmt->fetch()){
            echo $result[0];
        }
    }
    public function loadGroup($id){
       $dbh = $this->getDb();

        $stmt = $dbh->prepare('SELECT * FROM groupe WHERE id = :id');
        $arg=[
            ':id' => $id
        ];
        $stmt->execute($arg);
        while ($result = $stmt->fetch()){


            global $id;
            $id = $result['id'];

            global $nom;
            $nom = $result['nom'];

            global $contenu;
            $contenu = $result['contenu'];

            global $desc;
            $desc = $result['description'];

            global $dispo;
            $dispo = $result['dispo'];

            global $id_creator;
            $id_creator = $result['id_creator'];

        }
    }

    public function loadUserGroup($iduser){
        $dbh = $this->getDb();
        $stmt = $dbh->prepare('SELECT * FROM membregroupe WHERE id_user = :userid');
        $arg=[
            ':userid' => $iduser
        ];
        $stmt->execute($arg);
        while ($result = $stmt->fetch()){

            $groupid = $result['id_groupe'];
        }

        $stmt = $dbh->prepare('SELECT * FROM groupe WHERE id = :groupid');
        $arg=[
            ':groupid' => $groupid
        ];
        $stmt->execute();
        while ($result = $stmt->fetch()){

            $id = $result['id'];

            $nom = $result['nom'];

            $contenu = $result['contenu'];

            $desc = $result['description'];

            $dispo = $result['dispo'];

            $id_creator = $result['id_creator'];

            echo "<div class='section'>
      <div class='container'>
        <div class='row'>
          <div class='col-md-8 col-md-offset-2'>
            <img src='img/groupe/$contenu' href='groupeview.php?id=$id'class='center-block img-responsive'>
            <h1 class='text-center encadrement'>$nom</h1>
            <p class='text-center'>$desc</p>
          </div>
        </div>
      </div>
    </div>";
        }
    }

    public function createGroup($nom, $contenu, $dispo, $id_user){
        $dbh = $this->getDb();
        $stmt = $dbh->prepare('INSERT INTO groupe(id, nom, contenu, dispo, id_creator) values(NULL, :nom, :contenu, dispo, :id_creator)');
        $arg=[
            ':nom' => $nom,
            ':contenu' => $contenu,
            ':dispo' => $dispo,
            ':id_creator' => $id_user
        ];
        $stmt->execute($arg);


        $stmt = $dbh->prepare('SELECT * FROM groupe WHERE nom = :nom AND id_creator = :id_creator');
        $arg=[
            ':nom' => $nom,
            ':id_creator' => $id_user
        ];
        $stmt->execute($arg);
        while ($result = $stmt->fetch()){

            $id_groupe = $result['id'];

        }

        $stmt = $dbh->prepare('INSERT INTO membregroupe(id_groupe, id_user) values(:id_groupe, :id_user)');
        $arg=[
            ':id_groupe' => $id_groupe,
            ':id_user' => $id_user
        ];
        $stmt->execute($arg);
    }

    public function getIdUserByName($iduser){
       $dbh = $this->getDb();
        $stmt = $dbh->prepare('SELECT pseudo FROM users WHERE id = :id');
        $arg=[
            ':id' => $iduser
        ];
        $stmt->execute($arg);
        while ($result = $stmt->fetch()){
            global $pseudo;
            $pseudo = $result['pseudo'];
        }
    }

    public function addMemberToGroup($id_user, $id_groupe){
      $dbh = $this->getDb();
        $stmt = $dbh->prepare('INSERT INTO membregroupe(id_groupe, id_user) values(:id_groupe, :id_user)');
        $arg=[
            ':id_groupe' => $id_groupe,
            ':id_user' => $id_user
        ];
        $stmt->execute($arg);
    }

    public function joinGroup($id_groupe, $id_user) {
        $dbh = $this->getDb();
        $stmt = $dbh->prepare('SELECT dispo FROM groupe WHERE id = :groupid');
        $arg=[
            ':groupid' => $id_groupe
        ];
        $stmt->execute($arg);
        while ($result = $stmt->fetch()){
            $dispo = $result['dispo'];
        }
        if($dispo != 'prv'){
            $stmt = $dbh->prepare('INSERT INTO membregroupe(id_groupe, id_user) values(:id_groupe, :id_user)');
            $arg=[
                ':id_groupe' => $id_groupe,
                ':id_user' => $id_user
            ];
            $stmt->execute($arg);
        }
    }

    public function getPublicGroup(){
       $dbh = $this->getDb();

        $stmt = $dbh->prepare('SELECT * FROM groupe WHERE dispo = "pbc"');
        $stmt->execute();
        while ($result = $stmt->fetch()){

            $id = $result['id'];

            $nom = $result['nom'];

            $contenu = $result['contenu'];

            $desc = $result['description'];

            $dispo = $result['dispo'];

            $id_creator = $result['id_creator'];

            echo "<div class='section'>
      <div class='container'>
        <div class='row'>
          <div class='col-md-8 col-md-offset-2'>
            <img src='img/groupe/$contenu' href='groupeview.php?id=$id'class='center-block img-responsive'>
            <h1 class='text-center encadrement'>$nom</h1>
            <p class='text-center'>$desc</p>
          </div>
        </div>
      </div>
    </div>";

        }
    }
    public function getGroup(){
     $dbh = $this->getDb();

        $stmt = $dbh->prepare('SELECT * FROM groupe');
        $stmt->execute();
        while ($result = $stmt->fetch()){

            $id = $result['id'];

            $nom = $result['nom'];

            $img= $result['img'];
            $date= $result['date'];

            $desc = $result['description'];

            $dispo = $result['dispo'];

            $id_creator = $result['id_creator'];


            echo "
            <div class='col-md-3 col-md-offset-1 'style='padding-bottom:40px;'>
            <div class='col-md-12  thumbnail article' >
             <a href='#'>
                        <div class='caption'>
                            <h4>$nom</h4>
                            <p>$desc</p>
                            <p>$dispo<br> $date</p>

                        </div>
                        </a>
                        <img src='../img/$img' alt='...'></a>

                    </div><form>
                     <input class='id' name='id' value='$id '>
                    <input type='submit' class='col-md-12  del'   name='supprgroupe' value='Supprimer' style='margin-top:-20px;'>
                    </form></div>";
        }

    }

    public function removeGroup($id_user, $id_groupe){

        $stmt = $dbh->prepare('SELECT * FROM membregroupe');
        $stmt->execute();
        while ($result = $stmt->fetch()){
            $userid = $result['id_user'];
            $groupid = $result['id_groupe'];
        }
        if($id_user == $userid){
            $stmt = $dbh->prepare('DELETE FROM membregroupe WHERE id_user = :id_user AND id_groupe = :id_groupe');
            $arg=[
                ':id_user' => $id_user,
                ':id_groupe' => $id_groupe
            ];
            $stmt->execute($arg);
        }
    }

    public function loadStatu($id_groupe){
       $dbh = $this->getDb();

        $stmt = $dbh->prepare('SELECT * FROM groupecontenu WHERE id_groupe = :id_groupe');
        $arg=[
            ':id_groupe' => $id_groupe
        ];
        $stmt->execute($arg);
        while ($result = $stmt->fetch()){

            $id = $result['id'];

            $auteur = $result['auteur'];

            $date = $result['ladate'];

            $statu = $result['status'];

            $idgroupe = $result['id_groupe'];
            echo "
                    <div class='section'>
      <div class='container'>
        <div class='row'>
          <div class='col-md-6'><br><br><br>
            <div class='panel panel-default'>
            <div class='panel-body'>
            <div class='pull-left media'>
              <img class='img-responsive' src='img/groupe/statu.jpg'>
            </div>
	            <div class='pull-right'><br>
                <b><a href='#'>$auteur</a></b>
              </div><br><br><br>
            <center><p>$statu<hr></center>
            </p>
            <center>
            <a class='btn btn-success' href='#'>+1</a>
            <a class='btn btn-warning' href='#'>Commentaire</a>
            </center>

            </div>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>";
        }
    }


    public function addStatu($pseudo, $statut, $id_groupe){
        $date = date('d/m/Y');
        $dbh = $this->getDb();
        $stmt = $dbh->prepare('INSERT INTO groupecontenu(id, auteur, ladate, status, id_groupe) values(NULL, :pseudo, :ladatee, :status, :id_groupe)');
        $arg=[
            ':pseudo' => $pseudo,
            ':ladate' => $date,
            ':status' => $statut,
            ':id_groupe' => $id_groupe
        ];
        $stmt->execute($arg);
    }

    public function removeStatu($id_user,$pseudo, $id_groupe){
$dbh = $this->getDb();
        $stmt = $dbh->prepare('SELECT * FROM groupecontenu');
        $stmt->execute();
        while ($result = $stmt->fetch()){
            $userid = $result['auteur'];
            $groupid = $result['id_groupe'];
        }
        if($id_user == $userid){
            $stmt = $dbh->prepare('DELETE FROM groupecontenu WHERE auteur = :auteur AND id_groupe = :id_groupe');
            $arg=[
                ':auteur' => $pseudo,
                ':id_groupe' => $id_groupe
            ];
            $stmt->execute($arg);
        }
    }
}


?>