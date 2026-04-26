<?php
require_once 'models/query.php';
$query = new query;

$user= $query->selectWithID('*', 'guest', 'id_animaux is null');

?>

<head>

    <link rel="stylesheet" href="css/style.css">
    <script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js" integrity="sha384-3LK/3kTpDE/Pkp8gTNp2gR/2gOiwQ6QaO7Td0zV76UFJVhqLl4Vl3KL1We6q6wR9" crossorigin="anonymous"></script>


</head>
<nav></nav>


<div class="header-spacer"></div>
<div class="container ">
    <div class="row justify-content-center pres">
        <p class="col-10 col-lg-8">
            <strong>Bonjour</strong>,<br>Ouverture de nuit exceptionnelle du Zoo de Caro.<br>Les animaux du zoo sont invités à se préparer pour cette soirée inédite car les visiteurs viendront nombreux pour les admirer.
        </p>
        <span class="col-7 col-lg-6 timer"id="timer"></span>
        <p class="col-10 col-lg-8">Voici la procédure pour intégrer notre établissement :<p>
        <ul>
            <li>Choisir un animal sur la carte.</li>
            <li>Sélectionner votre nom dans la liste.</li>
            <li>Confirmer votre choix en cliquant sur "REJOINDRE" </li>
        </ul>
        <div class="col-10 col-lg-8 alert">
            <ul >   
                <li> Nous vous rappelons que le nombre d'enclos n'est pas extensible et qu'il s'agit donc d'être rapide. </li>
                <li> Les couples d'animaux sont automatiquement enregistrés de la même espèce.</li>
            </ul>
        </div>
    </div>
</div>

<div class="container ">
    <div class="row">
        <div class="col-12 col-lg-12 map">
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('panda')"width="100%" src="img/panda.png"></div>
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('pingouin')"width="100%" src="img/pingouin.png"></div>
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('crocodile')"width="100%" src="img/crocodile.png"></div>
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('lion')"width="100%" src="img/lion.png"></div>
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('paresseux')"width="100%" src="img/paresseux.png"></div>
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('hippopotame')"width="100%" src="img/hippo.png"></div>
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('rhinocéros')"width="100%" src="img/rhino.png"></div>
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('girafe')"width="100%" src="img/girafe.png"></div>
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('dauphin')"width="100%" src="img/dauphin.png"></div>
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('flamant rose')"width="100%" src="img/flamant.png"></div>
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('singe')"width="100%" src="img/singe.png"></div>
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('tigre')"width="100%" src="img/tigre.png"></div>
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('ours')"width="100%" src="img/ours.png"></div>
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('kangourou')"width="100%" src="img/kangourou.png"></div>
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('zèbre')"width="100%" src="img/zebre.png"></div>
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('phoque')"width="100%" src="img/phoque.png"></div>
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('ours polaire')"width="100%" src="img/ourspolaire.png"></div>
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('éléphant')"width="100%" src="img/elephant.png"></div>
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('requin')"width="100%" src="img/requin.png"></div>
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('poulpe')"width="100%" src="img/poulpe.png"></div>
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('calamar')"width="100%" src="img/calamar.png"></div>
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('perroquet')"width="100%" src="img/peroquet.png"></div>
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('cameleon')"width="100%" src="img/cameleon.png"></div>
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('tortue')"width="100%" src="img/tortue.png"></div>
            <div class="col-1 col-lg-1 animals"><img onclick="poppup('escargot')"width="100%" src="img/escargot.png"></div>
        </div>
    </div>

</div>
<div class="col-10 col-lg-8 popup align-content-center" >
    <span onclick="closeit('popup')">X</span>
    <h1 id="animals" ></h1>
    <form >
        <div class="col-10" id="alert"></div>
        <select name="users" onchange="showUser(this.value)"><option value="">Votre nom:</option><?php foreach($user as $person){?><option value="<?php echo $person['id']?>"><?php echo $person['name']." ".$person['lastname']; ?></option> <?php  } ?> </select>


        <div class="col-10 col-md-10 binome"><b id="txtHint" ></b></div>
        <div class="col-10 col-md-10 link">Exemple de déguisement:<br><b id="txt2"></b></div>
        <div class="col-12 col-md-12"style="display:flex;">
            <div class="col-8 col-md-9" style="padding:0;"><img src="" class="imageanimal" width="100%"/></div><div  id='choose' class='col-4 col-lg-2 ani' style="display:flex;"><p style="margin:auto;">REJOINDRE</p></div>
        </div>
    </form>
    <br>

</div>
<div class="col-12 col-lg-12 validate align-content-center" >
    <span onclick="closeit('validate')">X</span>
    <div class="col-12"style="display:flex;">
    <div class="col-5"><img src="img/Caro_genial.png" width="100%"/></div>
    <div class="col-7 "style="display:flex;"><p style="margin:auto;">"Je suis heureuse de te compter parmi nous pour cette soirée inédite !"</p></div>
    </div>
</div>




<script src="js/functions.js"></script>
<script>
    var date1 = (new Date("<?php echo date('M d H:i:s Y');?>")).getTime();
    var date2 = (new Date ("Sep 15 00:17:00 2018")).getTime();
    //    setInterval(function(){pictanimals($('#animals').text())},500);
    function Rebour() {

        var sec = (date2 - date1) / 1000;
        var n = 24 * 3600;
        if (sec > 0) {
            j = Math.floor (sec / n);
            h = Math.floor ((sec - (j * n)) / 3600);
            mn = Math.floor ((sec - ((j * n + h * 3600))) / 60);
            sec = Math.floor (sec - ((j * n + h * 3600 + mn * 60)));
            $("#timer").html(  j +" j "+ h +" h "+ mn +" min "+ sec + " s ");
            date1 += 1000;
        }
        var tRebour=setTimeout ("Rebour();", 1000);
    }

    Rebour();
</script>
