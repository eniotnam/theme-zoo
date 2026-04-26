
<div class="container">
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12" style="margin-top:-70px">
            <?php
            if (isset($_SESSION['connected'])){
            ?>
            <h1>Vos favoris</h1>
        </div>
        <div class="row">
            <div class="col-lg-offset-1 col-lg-10 col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10">
                <div class="champs">
                    <div class="desc">
                        <div class="col-md-2 col-md-offset-1"> <div class="follow"><h4>Vos partages</h4></div></div>
                        <div class="col-md-2 col-md-offset-1"><div class="follow"><h4>Vos likes</h4> </div></div>
                        <div class="col-md-2 col-md-offset-1"><div class="follow"><h4>Les personnes que vous suivez</h4></div></div>
                        <div class="col-md-2 col-md-offset-1"><div class="follow"><h4>Vos groupes favoris</h4></div></div>
                    </div>
                </div>
            </div>
        </div><br> <br><br> <br><br> <br>

    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-success"><a href="enregistrement.php"> Voir vos <br> enregistrements</a></button>

            </div>   
        </div>
    </div>
    <?php
            }
    ?>
    
   
    
            