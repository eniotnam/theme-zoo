<?php
require_once 'db.php';
class event extends db{

    public function getDb(){
        return Db::getInstance();
    }

    public function addevent($titre,$nom,$dispo,$descri,$date,$img,$fil){
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


                $reqtitre = $dbh->prepare("SELECT * FROM event WHERE nom = ? ");
                $reqtitre->execute(array($titre));
                $titreexist= $reqtitre->rowCount();
                if($titreexist == 0)
                {

                    if(in_array($extension, $extension_valid) && in_array($mime, $mime_valid)){
                        move_uploaded_file($fil['tmp_name'], '../img/' . $img);

                        $insertmbr =$dbh->prepare("INSERT INTO event(nom, dispo, id_creator, description, date, img) VALUES (?, ?, ?, ?, ?, ? ) ");
                        $insertmbr -> execute(array($titre, $dispo, $nom, $descri, $date, $img));

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

    public function supprevent($id){
        $dbh = $this->getDb();
        if( !empty($id) )
        {
            $requser = $dbh->prepare("SELECT * FROM event WHERE id = ?  ");
            $requser->execute(array($id));


            $userexist = $requser->rowCount();
            if($userexist == 1)
            {
                $req = $dbh->prepare('DELETE FROM event WHERE id = :id ');
                $req->execute([
                    ':id' => $id]);


            }
            else
            {
                $erreur = "Mauvais mail ou mot de passe incorrect !";
            }
        }
    }


    public function getEvent(){
        $dbh = $this->getDb();
        $stmt = $dbh->prepare('SELECT * FROM event ');
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
                    <input type='submit' class='col-md-12  del'   name='supprevent' value='Supprimer' style='margin-top:-20px;'>
                    </form></div>";
        }
    }

    public function countEvent(){
        $dbh = $this->getDb();

        $stmt = $dbh->prepare('SELECT COUNT(*) as somme FROM event');
        $stmt->execute();

        while ($result = $stmt->fetch()){
            echo $result[0];
        }
    }

    public function loadEvent(){
        $stmt = $dbh->prepare('SELECT * FROM event');
        $stmt->execute();
        while ($result = $stmt->fetch()){


            global $id;
            $id = $result['id'];

            global $nom;
            $nom = $result['nom'];

            global $contenu;
            $contenu = $result['contenu'];

            global $dispo;
            $dispo = $result['dispo'];

            global $id_creator;
            $id_creator = $result['id_creator'];
        }
    }

    public function loadUserEvent($iduser){
        $stmt = $dbh->prepare('SELECT * FROM eventmembre WHERE id_user = :userid');
        $arg=[
            ':userid' => $iduser
        ];
        $stmt->execute($arg);
        while ($result = $stmt->fetch()){

            $eventid = $result['id_event'];
        }

        $stmt = $dbh->prepare('SELECT * FROM event WHERE id = :eventid');
        $arg=[
            ':eventid' => $eventid
        ];
        $stmt->execute();
        while ($result = $stmt->fetch()){


            global $id;
            $id = $result['id'];

            global $nom;
            $nom = $result['nom'];

            global $contenu;
            $contenu = $result['contenu'];

            global $dispo;
            $dispo = $result['dispo'];

            global $id_creator;
            $id_creator = $result['id_creator'];
        }
    }

    public function createEvent($nom, $contenu, $dispo, $id_user){
        $stmt = $dbh->prepare('INSERT INTO event(id, nom, contenu, dispo, id_creator) values(NULL, :nom, :contenu, dispo, :id_creator)');
        $arg=[
            ':nom' => $nom,
            ':contenu' => $contenu,
            ':dispo' => $dispo,
            ':id_creator' => $id_user
        ];
        $stmt->execute($arg);


        $stmt = $dbh->prepare('SELECT * FROM event WHERE nom = :nom AND id_creator = :id_creator');
        $arg=[
            ':nom' => $nom,
            ':id_creator' => $id_user
        ];
        $stmt->execute($arg);
        while ($result = $stmt->fetch()){

            $id_event = $result['id'];

        }

        $stmt = $dbh->prepare('INSERT INTO eventmembre(id_event, id_user) values(:id_event, :id_user)');
        $arg=[
            ':id_event' => $id_event,
            ':id_user' => $id_user
        ];
        $stmt->execute($arg);
    }

    public function addMemberToEvent($id_user, $id_event){
        $stmt = $dbh->prepare('INSERT INTO eventmembre(id_event, id_user) values(:id_event, :id_user)');
        $arg=[
            ':id_event' => $id_event,
            ':id_user' => $id_user
        ];
        $stmt->execute($arg);
    }

    public function joinEvent($id_event, $id_user) {
        $stmt = $dbh->prepare('SELECT dispo FROM event WHERE id = :eventid');
        $arg=[
            ':eventid' => $id_event
        ];
        $stmt->execute($arg);
        while ($result = $stmt->fetch()){
            $dispo = $result['dispo'];
        }
        if($dispo != "prv"){
            $stmt = $dbh->prepare('INSERT INTO membreevent(id_event, id_user) values(:id_event, :id_user)');
            $arg=[
                ':id_event' => $id_event,
                ':id_user' => $id_user
            ];
            $stmt->execute($arg);
        }
    }

    public function getPublicEvent() {

        $stmt = $dbh->prepare('SELECT * FROM event WHERE dispo = pbc');
        $stmt->execute();
        while ($result = $stmt->fetch()){
            global $id;
            $id = $result['id'];

            global $nom;
            $nom = $result['nom'];

            global $contenu;
            $contenu = $result['contenu'];

            global $dispo;
            $dispo = $result['dispo'];

            global $id_creator;
            $id_creator = $result['id_creator'];
        }
    }
    public function removeEvent($id_user, $id_event){

        $stmt = $dbh->prepare('SELECT * FROM membreevent');
        $stmt->execute();
        while ($result = $stmt->fetch()){
            $userid = $result['id_user'];
            $eventid = $result['id_event'];
        }
        if($id_user == $userid){
            $stmt = $dbh->prepare('DELETE FROM membreevent WHERE id_user = :id_user AND id_event = :id_event');
            $arg=[
                ':id_user' => $id_user,
                ":id_event" => $id_event
            ];
            $stmt->execute($arg);
        }
    }

    public function addStatu($id_user, $statut, $id_event){
        $date = date("d m Y");
        $stmt = $dbh->prepare('INSERT INTO eventcontenu(id, auteur, ladate, status, id_event) values(NULL, :id_user, :ladatee, :status, :id_event)');
        $arg=[
            ':id_user' => $id_user,
            ':ladate' => $date,
            ':status' => $statut,
            ':id_event' => $id_event
        ];
        $stmt->execute($arg);
    }

    public function removeStatu($id_user, $id_event){

        $stmt = $dbh->prepare('SELECT * FROM eventcontenu');
        $stmt->execute();
        while ($result = $stmt->fetch()){
            $userid = $result['auteur'];
            $eventid = $result['id_event'];
        }
        if($id_user == $userid){
            $stmt = $dbh->prepare('DELETE FROM eventcontenu WHERE auteur = :auteur AND id_event = :id_event');
            $arg=[
                ':auteur' => $id_user,
                ":id_event" => $id_event
            ];
            $stmt->execute($arg);
        }
    }
}
?>