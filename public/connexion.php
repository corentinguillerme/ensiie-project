<?php

session_start();

require '../vendor/autoload.php';

$dbName = getenv('DB_NAME');
$dbUser = getenv('DB_USER');
$dbPassword = getenv('DB_PASSWORD');
$connection = new PDO("pgsql:host=postgres user=$dbUser dbname=$dbName password=$dbPassword");

$userRepository = new \User\UserRepository($connection);
$users = $userRepository->fetchAll();

if (!isset($_POST['mdp'])) {
	echo '<html><head><title>Page de connexion de Sciience</title><link rel="stylesheet" href="style.css"></head><body><header>
        <a href="index.php"><img src="./titre.png"/></a>
    </header><section class="connect"><div class="container"><div class="grand-titre">Bienvenue sur la page de connexion de Sciience</div>';
	echo '<form action="connexion.php" method="Post">
	Pseudo :<br>
	<input type="text" name="pseudo"><br>
	Mot de Passe :<br>
	<input type="password" name="mdp"><br>
	<input type="submit" class="butcon" value="Valider"></form>';
}
else {
	$pseudo=$_POST['pseudo'];
	$mdp=$_POST['mdp'];
	foreach ($users as $user) {
		if ($user->getPseudo() == $pseudo) {
			if (password_verify($mdp, $user->getMdp())) /*password_verify($user->getMdp(), $mdp) $user->getMdp() == $mdp */{
				$_SESSION["id_user"] = $user->getId();
				header("Location:index.php");
			}
		}
	}
	echo '<html><head><title>Page de connexion de Sciience</title><link rel="stylesheet" href="style.css"></head><body><header>
        <a href="index.php"><img src="./titre.png"/></a>
    </header><section class="connect"><div class="container"><div class="grand-titre">Bienvenue sur la page de connexion de Sciience</div>';
	echo '<p>Mot de passe erroné ou pseudo invalide</p>';
	echo '<p><a href=connexion.php>Cliquez ici</a> pour réessayer</p>';
}

?>
</div>
</section>
</body>
</html>

