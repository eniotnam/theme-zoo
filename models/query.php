<?php
require_once 'db.php';
class query extends db{

    public function getDb(){
        return Db::getInstance();
    }


    //    public function LoginAdmin($mail, $password){
    //        $dbh = $this->getDb();
    //        $stmt = $dbh->prepare('SELECT * FROM users WHERE email = :mail, pwd = :pwd ');
    //        $stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
    //        $stmt->bindValue(':pwd', $password, PDO::PARAM_STR);
    //        $stmt->execute();
    //        $users = $stmt->rowCount();
    //        if ($users == 1) {
    //            $_SESSION['connected'] = true;
    //            $_SESSION['rank'] = $users[0]['rank'];
    //            if($_SESSION['rank'] == 'Admin'){
    //                session_start();
    //                header('Location:/admin/');
    //            }
    //            header('Location:index.php');
    //        }
    //    }



    public function select($col, $table){
        $dbh = $this->getDb();
        $query = "SELECT $col FROM $table";
        $stmt = $dbh->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result;
    }
    //SELECT FROM WHERE
    public function selectWithID($col, $table, $details){
        $dbh = $this->getDb();
        $query = "SELECT $col FROM $table WHERE $details";
        $stmt = $dbh->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }
    public function selectWith($col, $table, $details,$value){
        $dbh = $this->getDb();

        $query = "SELECT $col FROM $table WHERE $details = ?";
        $stmt = $dbh->prepare($query);
        $stmt->execute(array($value));
        $result = $stmt->fetch();
        return $result;
    }

    // SELECT FROM
    public function selectAll($col, $table){
        $dbh = $this->getDb();
        $query = "SELECT $col FROM $table";
        $stmt = $dbh->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }
    public function insert($table, $col , $new ){
        $dbh = $this->getDb();
        $news=explode(',',$new);
        $query = "INSERT INTO $table ( $col ) VALUES ( ?,?)";
        $stmt = $dbh->prepare($query);
        $stmt->execute(array($news[0],$news[1]));

    }
    public function insertd($table, $col , $new ){
        $dbh = $this->getDb();
        $news=explode(',',$new);
        $query = "INSERT INTO $table ( $col ) VALUES ( ?,?,?)";
        $stmt = $dbh->prepare($query);
        $stmt->execute(array($news[0],$news[1],$news[2]));

    }
    public function delete($table, $id){
        $dbh = $this->getDb();
        $query = "DELETE FROM $table WHERE id = $id";
        $stmt = $dbh->prepare($query);
        $stmt->execute();
    }
    public function update($table, $id , $id2){
        $dbh = $this->getDb();

        $query = "UPDATE $table set $id WHERE $id2 ";
        $stmt = $dbh->prepare($query);
        $stmt->execute();

    }
    public function updated($table, $id , $id2){
        $dbh = $this->getDb();

        $query = "UPDATE $table set mdp=? WHERE id=? ";
        $stmt = $dbh->prepare($query);
        $stmt->execute(array($id,$id2));

    }
    public function Counter($table){
        $dbh = $this->getDb();

        $stmt = $dbh->prepare('SELECT COUNT(*) as somme FROM '.$table );
        $stmt->execute();

        while ($result = $stmt->fetch()){
            echo $result[0];
        }
    }
    public function Counterby($table,$col){
        $dbh = $this->getDb();

        $stmt = $dbh->prepare('SELECT COUNT(*) as somme FROM '.$table.' Where '.$col);
        $stmt->execute();

        while ($result = $stmt->fetch()){
            echo $result[0];
        }


    }
    
    function autolink($string,$separator=" ")
    {
        $content = explode(' ',$string);
        $sentence ="";
        foreach($content as $content2){
            if(preg_match("@(https://[^ ]+)@",$content2,$f)){

                $nom = preg_split('/[.]+/i',$f[0]);
                //        $nom= parse_url($til, PHP_URL_HOST);
                //        echo $f;
                $sentence.= " -";
                $sentence.= preg_replace("@(https://[^ ]+)@", "<a class='lien' href=\"$1\" target='_blank'>".ucfirst($nom[1])."</a>", $content2);
            }
            else{
                $sentence.=" ".$content2." ";
            }

            $sentence.= $separator ;

        }
        return $sentence;
    }
}