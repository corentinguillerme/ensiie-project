<?php

include("utils.php");


require '../vendor/autoload.php';

session_start();

//postgres
$dbName = getenv('DB_NAME');
$dbUser = getenv('DB_USER');
$dbPassword = getenv('DB_PASSWORD');
$connection = new PDO("pgsql:host=postgres user=$dbUser dbname=$dbName password=$dbPassword");

$userRepository = new \User\UserRepository($connection);
$livreRepository = new \Livre\LivreRepository($connection);
$livres=$livreRepository->fetchAll();
$reservationRepository = new \Reservation\ReservationRepository($connection);


// ajouter une redirection automatique si l'utilisateur n'est pas admin
if (!isset($_SESSION["id_user"])) {
    header("Location: index.php");
}
if (!(verifAdmin($_SESSION["id_user"]))) {
    header("Location: index.php");
}



//vérification de la validité des variables postées
if (isset($_POST['pseudo'])) {
    $okpseudo= !(verifPseudo($_POST['pseudo']));
    if ($okpseudo && livreReserve(PseudoToId($_POST['pseudo'])) != '') {
        $aunereserv=true;
    }
}
if (isset($_POST['id_livre'])) {
    $okid= !(verifId($_POST['id_livre']));
    if (estreservLivre($_POST['id_livre']) && (areservLivre($_POST['id_livre']) != PseudoToId($_POST['pseudo']))) {
        $dejareserv=true;
    }
    else {
        $dejareserv=false;
    }
    if (estEmprunte($_POST['id_livre'])) {
        $dejaemprunte=true;
    }
    else {
        $dejaemprunte=false;
    }
}





//Si tout est ok on réalise l'emprunt
//retirer de la table réservation si nécessaire
//update le livre dans sa table
if (isset($_POST['id_livre']) && isset($_POST['pseudo']) && $okpseudo && $okid && !($dejareserv)     && !($dejaemprunte)) {
    $tmpres=$reservationRepository->creeReservation($_POST['id_livre'], PseudoToId($_POST['pseudo']));
    $reservationRepository->deleteReservation($tmpres);

    $tmplivre=$livreRepository->fetchId($_POST['id_livre']);
    $tmplivre->setEmprunteur(PseudoToId($_POST['pseudo']));
    $date = new DateTime();
    $tmplivre->setDateEmprunt($date);
    $livreRepository->updateLivre($tmplivre);

    header("Location: index.php");

}






?>

<html>
<head>
    <meta charset="utf-8">
    <title>Emprunt</title>
    <link rel="stylesheet" href=".css">
</head>
<body>
    <div class="container">
    <h2>Page d'emprunt de livre (réservé aux admins)</h2>
    <nav>
         <!-- TODO recopier le nav-->
    </nav>

    <?php
    if (isset($dejareserv) && $dejareserv) {
        echo "<p>Ce livre est déjà réservé par un autre utilisateur</p>";
    }

    if(isset($dejaemprunte) && $dejaemprunte) {
        echo "<p>Ce livre est déjà emprunté par un autre utilisateur</p>";
    }
    ?>






    <!-- premier formulaire où l'admin peut saisir un utilisateur non obligatoire-->
    <h4>identification directe de l'emprunteur</h4>
    <form action="emprunt.php" method="POST">
        Pseudo de l'emprunteur :<br>
        <input id="f1pseudo" type="text" name="pseudo"><br>
        <input type="button" class="input" onclick="valide_emprunteur()" value="Valider">
        <input id="validerf1" style="display:none" type="submit" name="Envoyer">
    </form>

    <p id="f1error" style="display:none">Veuillez remplir le champ</p>


    <?php
    if (isset($okpseudo) && !($okpseudo)) {
        echo "<p>Pseudo Invalide</p>";
    }
    ?>




    <!-- second formulaire déjà prérempli si le premier à été validé-->
    <h4>Champs de réservation</h4>
    <form action="emprunt.php" method="POST">
        Id Livre :<br>
        <input id="f2idlivre" type="text" name="id_livre" value=<?php if (isset($aunereserv) && $aunereserv) echo livreReserve(PseudoToId($_POST['pseudo']))?>><br>
        Titre :<br>
        <input id="f2titre" type="text" name="titre" value=<?php if (isset($aunereserv) && $aunereserv) echo IdToTitre(livreReserve(PseudoToId($_POST['pseudo'])))?>><br>
        Pseudo emprunteur :<br>
        <input id="f2pseudo" type="text" name="pseudo" value=<?php if (isset($_POST['pseudo'])) echo $_POST['pseudo'] ?>><br>
        <input type="button" class="input" onclick="valide_formulaire()" value="Valider">
        <input id="validerf2" style="display:none" type="submit" name="Emprunter">
    </form>

    <p id="f2error" style="display:none">Veuillez remplir correctement tous les champs</p>

    <?php
    if (isset($okid) && !($okid)) {
        echo "<p>Id de Livre invalide</p>";
    }
    ?>
</div>


<h4>Affichage de la liste des livres disponibles</h4>
<input type="button" onclick="affichetable()" value="Afficher la liste des livres">

<p id="displaytable" style="display:none">
    <table class="table table-bordered table-hover table-striped">
    <thead style="font-weight: bold">
    <td>#</td>
    <td>titre</td>
    <td>emprunteur</td>
    <td>emprunter</td>
    </thead>
<?php 
    foreach ($livres as $livre) : ?>
    <tr>
    <td><?php echo $livre->getId() ?></td>
    <td><?php echo $livre->getTitre() ?></td>
    <td><?php if ($livre->getEmprunteur() !='') echo IdToPseudo($livre->getEmprunteur()) ?></td>
    <td>

        <form action="emprunt.php" method="POST">
        <input style="display:none" type="text" name="id_livre" value=<?php echo $livre->getId() ?>>
        <input style="display:none" type="text" name="titre" value=<?php echo $livre->getTitre() ?>>
        Pseudo :<input type="text" name="pseudo" value=<?php if (isset($_POST['pseudo'])) echo $_POST['pseudo'] ?>>
        <input type="submit" name="Valider" value="Valider">
    </form>


    </tr>
<?php endforeach; ?>
    </table>
</p>
</body>


<script>
    function valide_emprunteur() {
        tmp=document.getElementById("f1pseudo").value;
        if (tmp==''){
            document.getElementById("f1error").style.display="block";
        }
        else {
            document.getElementById("validerf1").click();
        }
    }


    function valide_formulaire() {
        tmpidlivre=document.getElementById("f2idlivre").value;
        tmptitre=document.getElementById("f2titre").value;
        tmppseudo=document.getElementById("f2pseudo").value;
        if (tmpidlivre=='' || tmptitre=='' || tmppseudo=='') {
            document.getElementById("f2error").style.display="block";
        }
        else {
            document.getElementById("validerf2").click();
        }
    }

    function affichetable() {
        if (document.getElementById("displaytable").style.display=="none") {
            document.getElementById("displaytable").style.display="block";
        }
        else {
            document.getElementById("displaytable").style.display="none";
        }
    }
</script>

</html>

