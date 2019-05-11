<?php
require '../vendor/autoload.php';

include "utils.php";

session_start();

//postgres
$dbName = getenv('DB_NAME');
$dbUser = getenv('DB_USER');
$dbPassword = getenv('DB_PASSWORD');
$connection = new PDO("pgsql:host=postgres user=$dbUser dbname=$dbName password=$dbPassword");

$userRepository = new \User\UserRepository($connection);
$livreRepository = new \Livre\LivreRepository($connection);
$auteurRepository = new \Auteur\AuteurRepository($connection);


$user_connected=isset($_SESSION["id_user"]);

$admin=false;

if ($user_connected) {//on récupère les info sur l'utilisateur courrant (si il est identifié)
//!\\ si vous le copiez vous devez avoir la ligne $userRepository = new \User\UserRepository($connection); plus haut
    $id_user=$_SESSION["id_user"];
    $user=$userRepository->fetchId($id_user);
    $admin=$user->getAdmin();
    $nom=$user->getNom();
    $prenom=$user->getPrenom();
    $pseudo=$user->getPseudo();
}

if ($user_connected) {//on récupère les info sur l'utilisateur courrant (si il est identifié)
    $id_user=$_SESSION["id_user"];
}

//si une recherche a été lancée précédemment, on génère la liste des livres à afficher en conséquence
if (isset($_POST['titre'])) {
	$livres=$livreRepository->fetchRechercheTitre($_POST['titre']);//TODOfonction qui cherche un fonction du titre
}
else {
	$livres=$livreRepository->fetchAll();
}

?>
<html>
<head>
	<meta charset="utf-8">
    <title>Bibliothèque</title>
    <link rel="stylesheet" href="style.css">
</head>
<div class="top"> <!--ajout d'un haut de page si l'utilisateur est admin ou si il est connecté-->
        <?php
        if ($user_connected) {
            echo "<TABLE >
      <TR>
        <TD class=\"bande1\" align=\"left\" WIDTH=\"100%\">Vous êtes connecté en tant que $nom \"$pseudo\" $prenom</TD>
        <TD style=\"border:none; height:30px\" align=\"right\"><form action=\"deconnection.php\"><input class=\"bande2\" type=\"submit\" value=\"Deconnexion\"></form></TD>
      </TR>
    </TABLE>";

            //"<p style=\"white-space: no-wrap\">Vous êtes connecté en tant que $nom \"$pseudo\" $prenom<div style=\"white-space: no-wrap\">Deconection</div> </p>";

        }
        else {
            echo "<TABLE >
      <TR>
        <TD class=\"bande1\" align=\"left\" WIDTH=\"100%\"></TD>
        <TD style=\"border:none; height:30px\" align=\"right\"><form action=\"connexion.php\"><input class=\"bande2\" type=\"submit\" value=\"Connexion\"></form></TD>
      </TR>
    </TABLE>";
        }
        
        ?>
    </div>
<body>
  <header>
        <img src="./titre.png"/>
    </header>
    <nav>
        <a href="index.php" class="rubrique">Accueil    </a>
        <a href="bibliotheque.php" class="rubrique">|   Bibliothèque    </a>
        <?php if ($user_connected): ?>
            <a href="espace_perso.php" class="rubrique">|   Espace perso    </a>
            <a href="review.php" class="rubrique">|   Review    </a>
            <?php endif; ?>
        <?php if ($user_connected && $admin): ?>
            <a href="liste_emprunts.php" class="rubrique">|   Liste   </a>
            <a href="ajout_livre.php" class="rubrique">|   Ajout livre   </a>
            <a href="rendu.php" class="rubrique">|   Retour   </a>
            <a href="emprunt.php" class="rubrique">|   Emprunt   </a>
        <?php endif; ?>
    </nav>
	<?php if (!($user_connected)) {
		echo "<p>Vous devez être connecté pour effectuer une réservation</p>";
	}
	?>
  <section>
	<div class="grand-titre">Bibliothèque</div>
	<div class="champ recherche">
		<h4>Rechercher un livre par titre</h4>
		<form action="bibliotheque.php" method="POST">
			Titre :<br>
			<input id="ftitre" type="text" name="titre"><br>
			<input type="button" class="butval" onclick="valide_ftitre()" value="Valider">
			<input id="validerftitre" style="display:none" type="submit" name="Valider">
		</form>
		<p id="ftitreerror" style="display:none">Veuillez remplir le champ</p>
	</div>
    <div class="contenu">
      <div class="res">Livres</div>
    	<table class="table table-bordered table-hover table-striped">
    <thead style="font-weight: bold">
      <th>Couverture</th>
    <th>titre</th>
    <th>publication</th>
    <th>editeur</th>
    <th>auteurs</th>
    <th>emprunteur</th>
    <th>review</th>
    <?php if ($user_connected) {
    	echo "<th>reserver</th>";
    }
    ?>
    </thead>
<?php
    foreach ($livres as $livre) : ?>
    <tr><?php $ID=$livre->getId(); ?>
    <td class="couv"><img height="160" width="100" src=<?php echo $livre->getImage() ?>></td>
    <td><?php echo $livre->getTitre() ?></td>
    <td><?php echo date_format ($livre->getPublication(), 'Y-m-d') ?></td>
    <td><?php echo $livre->getEdition() ?></td>
    <td>
    	<table>
    	<?php
    	$auteurs = $auteurRepository->fetchByLivre($livre->getId());
    	foreach ($auteurs as $auteur) : ?>
    		<tr><td style="height:50px;border:none"><?php echo $auteur->getAuteur(); ?></td></tr>
    	<?php endforeach; ?>
    </table></td>
    <td><?php if ($livre->getEmprunteur() != '') {
      echo IdToPseudo($livre->getEmprunteur());
    } ?></td>
    <td><form action="voir_review.php" method="POST"><input style="display:none" type="text" name="id_livre" value=<?php echo $livre->getId(); ?>><input class="butcan" type="submit" name="Voir les reviews" value="Voir les reviews"></form></td>
    <?php if ($user_connected) : ?>
    	<td><form action="reservation.php" method="POST"><input style="display:none" type="text" name="id_livre" value=<?php echo $livre->getId(); ?>><input style="display:none" type="text" name="id_user" value=<?php echo $id_user; ?>><input class="butcan" type="submit" name="Réserver" value="Réserver"></form></td>
    <?php endif; ?>
    </tr>
<?php endforeach; ?>
    </table>
    <p></p>
    </div>
  </section>
  <footer>On est vraiment trop style regardez ce quon a fait</footer>
   </body>

   <script>
   	function valide_ftitre() {
   		tmptitre=document.getElementById("ftitre").value;
   		if (tmptitre=='') {
   			document.getElementById("ftitreerror").style.display="block";
   		}
   		else {
   			document.getElementById("validerftitre").click();
   		}
   	}

   	function aff_auteurs(id) {
   		document.getElementById(id.toString()).style.display="block";
   		document.getElementById(id.toString()+"butt").style.display="none";
   	}

   </script>
</html>