<?php 
if(isset($_GET['ok'])){ // si on a envoyé des données avec le formulaire

    if(!empty($_GET['article'])){ // si les variables ne sont pas vides
    $user =17;
        $article = mysql_real_escape_string($_GET['article']);
        $type = mysql_real_escape_string($_GET['type']); // on sécurise nos données

        // puis on entre les données en base de données :
        $insertion = $bdd->prepare('INSERT INTO messages VALUES("", :id_article, :type,:id_user)');
        $insertion->execute(array(
            'id_article' => $article,
            'id_user' => $user,
            'type'=>$type
        ));

    }
    else{
        echo "Vous avez oublié de remplir un des champs !";
    }

}
?>