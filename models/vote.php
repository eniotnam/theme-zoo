<?php 

require_once 'db.php';

class vote extends db{
 public function getDb(){
      return Db::getInstance();
    }
 public function countVote(){
        $dbh = $this->getDb();
        
        $stmt = $dbh->prepare('SELECT COUNT(*) as somme FROM vote');
        $stmt->execute();

        while ($result = $stmt->fetch()){
            echo $result[0];
        }
    }
    
    public function countVoteNeutre(){
       $dbh = $this->getDb();
        
        $stmt = $dbh->prepare('SELECT COUNT(*) as somme FROM vote where type = neutre ');
        $stmt->execute();

        while ($result = $stmt->fetch()){
            echo $result[0];
        }
    }
    public function countVoteNo(){
         $dbh = $this->getDb();
        $non = 'non' ;
        $stmt = $dbh->prepare('SELECT COUNT(*) as somme FROM vote where type = "'.$non.'"');
        $stmt->execute();

        while ($result = $stmt->fetch()){
            echo $result[0];
        }
    } 
    public function countVoteYes(){
       $dbh = $this->getDb();
        $yes = 'yes' ;
        $stmt = $dbh->prepare('SELECT COUNT(*) as somme FROM vote where type = "'.$yes.'"');
        $stmt->execute();

        while ($result = $stmt->fetch()){
            echo $result[0];
        }
    }
public function like($type,$id_user,$id_article){
    require_once 'query.php';
    $query = new query;
    $dbh = $this->getDb();
    
   if($query->verif('vote','id_user="'.$id_user.'" AND id_article="'.$id_article.'"') > 0){
       if($query->verif('vote','id_user="'.$id_user.'"AND id_article="'.$id_article.'"AND type="'.$type.'"') > 0){
            $suppr = "Delete from vote where  id_user =$id_user AND id_article = $id_article  ";
        $stmt = $dbh->prepare($suppr);
        $stmt-> execute();
    header('location:index.php');
       }
       else{
            $add = "  ";
        $stmt = $dbh->prepare('UPDATE vote SET type = :type where id_user = :id_user AND id_article = :id_article');
           $arg = [
               ':type' => $type,
               ':id_user' => $id_user,
               ':id_article' => $id_article
           ];
        $stmt-> execute($arg);
       header('location:index.php');
       }
   }
    else{
         $adds = "INSERT INTO vote  (id_user ,id_article,type) VALUES (?,?,?) ";
        $stmt = $dbh->prepare($adds);
        $stmt-> execute(array($id_user,$id_article, $type));
      header('location:index.php');
    }
    
}
    
    







}