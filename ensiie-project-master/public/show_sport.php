<?php
require '../vendor/autoload.php';
require '../src/Sport/SportRepository.php';
require '../src/Sport/Sport.php';
require '../src/LogementSport/LogementSportRepository.php';
require '../src/LogementSport/LogementSport.php';
include("ini_session.php");
//postgres
$dbName = getenv('DB_NAME');
$dbUser = getenv('DB_USER');
$dbPassword = getenv('DB_PASSWORD');
$connection = new PDO("pgsql:host=postgres user=$dbUser dbname=$dbName password=$dbPassword");
$sportRepository = new \Sport\SportRepository($connection);
$sports = $sportRepository->fetchAll();
$userRepository = new \User\UserRepository($connection);
$usersParticipant = $userRepository->fetchAllParticipant();
$usersJury = $userRepository->fetchAllJury();
$usersOrganisateur = $userRepository->fetchAllOrganisateur();
$logementSportRepository = new \LogementSport\LogementSportRepository($connection);
$logementSports = $logementSportRepository->fetchAll();

$rows = $sportRepository->fetchAllUsers();
?>

<html lang="fr" dir="ltr">
<head>
    <title>Challenge Centrale Evry</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css" type="text/css">

    <?php include ("header.php")?>
</head>
<body>
<div class="abc ligne2">
<div id="block" class="clear">
    <table class="table1">
        <thead style="font-weight: bold">
            <td>Sport</td>
            <td>Prenom</td>
            <td>Nom</td>
            <td>Genre</td>
            <td>Lieu</td>
        </thead>
         <?php /** @var \User\User $user */
        
        foreach ($rows as $row) : ?>
            <tr>
                <td><?php echo $row->sport ?></td>
                <td><?php echo $row->prenom ?></td>
                <td><?php echo $row->unom ?></td>
                <td><?php echo $row->genre ?></td>
                <td><?php echo $row->lieu ?></td>
            </tr>
        <?php endforeach;?>
    </table>
<br>
<br>
<br>
<br>


    <table class="table1">
        <thead style="font-weight: bold">
            <td>Adresse Logement</td>
            <td>Sport</td>
            <td>Genre</td>
        </thead>
         <?php /** @var \User\User $user */
        
        foreach ($logementSports as $row) : ?>
            <tr>
                <td><?php echo $row->getAdresse() ?></td>
                <td><?php echo $row->getNom() ?></td>
                <td><?php echo $row->getGenre() ?></td>
            </tr>
        <?php endforeach;?>
    </table>
</div>
</div>
    <?php include("footer.php") ?>

</body>
</html>

