
<?php

require_once 'models/query.php';
$query = new query;

if(!empty($_GET['q'])){
    $q = $_GET['q'];    
    $id=$query->selectWithID('id_couple', 'guest', 'id='.$q);
    $person=$query->selectWithID('name,lastname', 'guest', 'id ='.$id[0]['id_couple']);

    if($id[0]['id_couple']){
        echo " et ".$person[0]['name']." ".$person[0]['lastname']."  ";
        echo "<input type='hidden' value='".$id[0]['id_couple']."' id='id_couple' name='id' >";}
    else{
        echo "";
    }
}

if(!empty($_GET['r'])){
    $r = $_GET['r'];
    $animal=$query->selectWithID('*', 'animals', 'name =\''.$r.'\'');

    if($animal){
        if($animal[0]['available']==null ){
            $site = $query->autolink($animal[0]['link'],'<br>');
            echo $site;
            echo "<input type='hidden' value='".$animal[0]['id']."' id='id_animals' name='id_animals' >";
        }
        else{
            $site = $query->autolink($animal[0]['link'],'<br>');
            echo $site;
        }
    }
}

if(!empty($_GET['a'])){
    $animals_id = $_GET['a'];
    $user_id = $_GET['b'];
    $user_couple_id = $_GET['c'];

    $query->update('animals', 'available = 1', 'id = \''.$animals_id.'\'');
    $query->update('guest', 'id_animaux =\''.$animals_id.'\'','id = \''.$user_id.'\'');

    if($user_couple_id != 'null' || !empty($user_couple_id )){
        $query->update('guest', 'id_animaux =\''.$animals_id.'\'','id = \''.$user_couple_id.'\'');
    }
    echo "vous êtes inscrit";
}