<?php
require '../vendor/autoload.php';
require '../src/User/projetControl.php';
$status = $_SESSION['status'];
//postgres
$dbName = getenv('DB_NAME');
$dbUser = getenv('DB_USER');
$dbPassword = getenv('DB_PASSWORD');
$bdd = new PDO("pgsql:host=postgres user=$dbUser dbname=$dbName password=$dbPassword");

?>
<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="projet.css">
</head>
<?php 
//require('con_.php');
//extract($_POST);
$nom=$_POST['nom'];

	$selection = $bdd->query("SELECT * FROM Livres WHERE titre= '$nom'");

       
  
?>
<!DOCTYPE html>
<html>
<head>
	<title>Resultat</title>
		<link rel="stylesheet" href="frontend/css/bootstrap.css">
	<link rel="stylesheet" href="frontend/css/font-awesome.css">
  
	<script type="text/javascript" src="frontend/js/jquery.js" ></script>
    <script type="text/javascript" src="frontend/js/dropdown.js"></script>
     <script type="text/javascript" src="frontend/js/script.js"></script>
      <script type="text/javascript" src="frontend/js/collapse.js"></script>
      <script type="text/javascript" src="frontend/js/button.js"></script>
      
</head>
<body >


<div class="container">
<div class="row">
	<?php 
	if(!empty($selection)){?>
<table class="table table-bordered table-condensed">
<thead><th>Reference</th>
    <th>Titre</th>
    <th>Auteur</th>
    <th>Genre</th>
    <th>disponibilite</th>
    <th>Reservation</th>

<?php
  if( $status == "administrateur")
  {
    echo "<th>Supression</th>";
  }
?>
</thead>
<tbody>
<?php 
 $req=$selection->fetch();
 	
 		?>
 	<tr>
    
    <td ><?php echo($req['reference']);?></td>
    <td ><?php echo($req['titre']);?></td>
    <td><?php echo($req['auteur']);?></td>
      <td ><?php echo($req['genre']);?></td>
    <td><?php echo($req['disponibilite']);?></td>
    <td>
    <a href="reserverlivre.php?reference=<?php echo($req['reference']);?> & disponibilite= <?php echo($req['disponibilite']);?> & titre=<?php echo($req['titre']);?>">Reserver<span class="fa fa-check-square-o"></span></a>
    </td>
    <?php
    if($status == "administrateur")
    {
        
    
        echo '      <td>
                        <form method = "post" action = "supprimerArticle.php">
                            <input type = "hidden" name = "reference" value = ' . $req["reference"] . '>
                            <input type = "hidden" name = "type" value = "Livres">
                            <input type = "submit" value = "Supprimer">
                        </form>
                    </td>';
        
   
    }

    ?>
    </tr>

   </tbody></table><?php
}else{?>
	 <div class="col-md-6 col-md-offset-3" style="box-shadow: 0px 0px 8px 8px #D0D0D0;font-family: Consolas;border-radius: 15px;">
	 	<h1 class="red">LIVRE NON DISPoNIBLE</h1></div><?php

}?>
   </tbody></table>
   <?php navigation($status); ?>

</div></div>
 </body></html>