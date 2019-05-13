<?php
require '../vendor/autoload.php';
require '../src/User/UserRepository.php';
require '../src/User/User.php';
require_once('../src/Move/MoveRepository.php');
require_once('../src/Move/Move.php');
require_once('../src/Spot/SpotRepository.php');
require_once('../src/Spot/Spot.php');
require_once('../src/SpotXmove/SpotXmoveRepository.php');
require_once('../src/SpotXmove/SpotXmove.php');

include ('view.php');

//démarre une session pour garder l'utilisateur connecté entre les pages
session_start();

//postgres
$dbName = getenv('DB_NAME');
$dbUser = getenv('DB_USER');
$dbPassword = getenv('DB_PASSWORD');
$connection = new PDO("pgsql:host=postgres user=$dbUser dbname=$dbName password=$dbPassword");

$userRepository = new \User\UserRepository($connection);
$users = $userRepository->fetchAll();
$moveRepository = new \Move\MoveRepository($connection);
$moves = $moveRepository->fetchAll();
$spotRepository = new \Spot\SpotRepository($connection);
$spots = $spotRepository->fetchAll();

//récupration de la ville entrée par l'utilisateur
if (isset($_GET['ville'])) $ville = $_GET['ville'];

//si un utilisateur ajoute un nouveau spot
if (isset($_POST['spotname'])) {
    $spot = new \Spot\Spot();
    //récupérer la latitude et la longitude ??
    $spot->setLongitude(0);
    $spot->setLatitude(0);
    $spot->setNom($_POST['spotname']);
    $spot->setVille($_POST['spotcity']);
    $spot->setNote($_POST['spotnote']);
    $spotRepository->addSpot($spot);
}
?>

<html>
<head>
    <!-- Latest compiled and minified CSS -->
    <!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"-->
    <?php my_head(); ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css"
   integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
   crossorigin=""/>

</head>
<body>

    <?php header_login(); ?>

<div class="container">

	<div id="search">
		<form action="index.php">
		<span style="font-size:140%">Trouve le spot le plus près de chez toi :</br></span>
		<input id="searchbar" type="text" name="ville" placeholder="Entrez votre ville">
		</form>
    </div>
    
    <div id="mapid">
		<script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"
   integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og=="
   crossorigin=""></script>
        <script>
		var mymap = L.map('mapid').setView([48.623447, 2.425771], 14);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 20,
    id: 'mapbox.streets',
    accessToken: 'your.mapbox.access.token'
}).addTo(mymap);
	</script>
    </div>
    <?php foreach ($spots as $spot) :?>
	 <?php $lat = $spot->getlatitude();
          	$long = $spot->getLongitude();
		$name = $spot->getNom();?>
		<script>
		 var marker = L.marker([<?php echo $lat?>,<?echo $long?>]).addTo(mymap);
    		 marker.bindPopup('<h3>"<?php echo $name?>"</h3>');
}
		</script>
		<?php endforeach;?>
    

	<?php if (!isset($_SESSION['mail'])) {
        //bouton de création de compte si l'utilisateur n'est pas connecté
        echo "<div calss=\"flex-container\" style=\"margin-top: 20px\">
                <button class=\"bouton\" style=\"margin-left:45%\">
                <a href=\"connexion.php\">Créez-vous un compte !</a>
                </div>";
    }?>

    <div class="article-container">

        <?php if (isset($_SESSION['mail'])) followed($_SESSION['mail']); ?>
        
        <div class="article">
            <form action="index.php" method="post">
            <span style="font-size:140%">Ajoute un Spot que tu as découvert :</br></span>
            <input type="text" name="spotname" required="true" placeholder="Entrez le nom du spot">
            <input type="text" name="spotcity" required="true" placeholder="Entrez la ville du spot">
            <input type="number" min="0" max="5" name="spotnote" placeholder="Entrez une note entre 0 et 5">
            <button class="bouton" type="submit" style="margin-top: 8px">envoyer</button>
            </form>
        </div>
    </div>
    

    <h3><?php echo 'Hello world from Docker! php' . PHP_VERSION; ?></h3>
    <table class="table table-bordered table-hover table-striped">
        <thead style="font-weight: bold">
            <td>#</td>
            <td>Firstname</td>
            <td>Lastname</td>
			<td>Age</td>
			<td>passws</td>
			<td>mail</td>
        </thead>
      <?php /** @var \User\User $user */
        foreach ($users as $user) : ?>
            <tr>
                <td><?php echo $user->getId() ?></td>
                <td><?php echo $user->getFirstname() ?></td>
                <td><?php echo $user->getLastname() ?></td>
				<td><?php echo $user->getAge() ?> years</td>
				<td><?php echo $user->getPassword() ?></td>
				<td><?php echo $user->getMail()  ?></td>
            </tr>
      <?php endforeach; ?>

    </table>

    <table class="table table-bordered table-hover table-striped">
        <thead style="font-weight: bold">
            <td>#</td>
            <td>nom</td>
            <td>difficulte</td>
        </thead>
      <?php /** @var \Move\Move $move */
        foreach ($moves as $move) : ?>
            <tr>
                <td><?php echo $move->getId() ?></td>
                <td><?php echo $move->getNom() ?></td>
                <td><?php echo $move->getDifficulte() ?></td>
            </tr>
      <?php endforeach; ?>

    </table>

    <table class="table table-bordered table-hover table-striped">
        <thead style="font-weight: bold">
            <td>#</td>
            <td>nom</td>
            <td>latitude</td>
            <td>longitude</td>
            <td>note</td>
            <td>ville</td>
        </thead>
      <?php /** @var \Spot\Spot $spot */
        foreach ($spots as $spot) : ?>
            <tr>
                <td><?php echo $spot->getId() ?></td>
                <td><?php echo $spot->getNom() ?></td>
                <td><?php echo $spot->getlatitude() ?></td>
                <td><?php echo $spot->getLongitude() ?></td>
                <td><?php echo $spot->getNote() ?></td>
                <td><?php echo $spot->getVille() ?></td>
            </tr>
      <?php endforeach; ?>

    </table>

</div>
<footer>
<?php footer();?>
</footer>
</body>
</html>
