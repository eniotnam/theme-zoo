<?php


require_once('models/groupe.php');
$groupe = new groupe;

?>

  <body>
  <hr>
<?php
  if (isset($_SESSION['connected'])){
$groupe->loadGroupall();
}
?>
      
            
  </body>
