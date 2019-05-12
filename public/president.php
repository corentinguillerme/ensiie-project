<?php
include('./admin/functions.php');
// On prolonge la session
session_start();
// On teste si la variable de session existe et contient une valeur
if(empty($_SESSION['login']) || empty($_SESSION['president'])) 
{
  // Si inexistante ou nulle, on redirige vers le formulaire de login
  header('Location: authentification.php');
  exit();
}

  //Gros machin copié depuis authentification.php
require '../vendor/autoload.php';
//postgres
$dbName = getenv('DB_NAME');
$dbUser = getenv('DB_USER');
$dbPassword = getenv('DB_PASSWORD');
$connection = new PDO("pgsql:host=postgres user=$dbUser dbname=$dbName password=$dbPassword");

$userRepository = new \User\UserRepository($connection);
$users = $userRepository->fetchAll();
$user = $users[$_SESSION['login']];

$assos_pres = $connection->query('select * from associations where president='.$user->getId())->fetchAll(\PDO::FETCH_OBJ);

displayHeader();

//un président doit pouvoir:
// - choisir quelle asso modifier
// - transmettre le role de président
// - rajouter,modifier, enlever un évènement
// - consulter, modifier, rajouter, enlever la participation d'un élève a un évènement
 
// début suppression evenement
if (array_key_exists('suppr',$_POST) && !empty($_POST['idevent'])){
	if ($connection->query("delete from events where id_event='".$_POST['idevent']."'")){
		echo "deleted";
	}	
}
//fin suppression evenement

//debut modification evenement
if (!empty($_POST['nameevent'])){
	$connection->query("update events set name='".$_POST["nameevent"]."' where id_event=".$_POST["idevent"]);
}
if (!empty($_POST['dateevent'])){
	$date = new DateTime($_POST['dateevent']);
	$date= $date->format('Y-m-d');
	$connection->query("update events set date_ev='".$date."' where id_event=".$_POST["idevent"]);
}

if (!empty($_POST['coeffevent'])){
	$connection->query("update events set coeff_event=".$_POST["coeffevent"]." where id_event=".$_POST["idevent"]);
}
if (!empty($_POST['descriptionevent'])){
	$connection->query("update events set description_event='".$_POST["descriptionevent"]."' where id_event=".$_POST["idevent"]);
}
//fin modification evenement

//debut création evenement
if (!empty($_POST['event_name']) && !empty($_POST['event_date']) && !empty($_POST['event_desc']) && !empty($_SESSION['association']) && !empty($_POST['coeff_event'])){
	$date = new DateTime($_POST['event_date']);
	$date= $date->format('Y-m-d');
	$connection->query("insert into events(name,id_asso,coeff_event,date_ev,description_event) values ('".$_POST['event_name']."',".$_SESSION['association'].",".$_POST['coeff_event'].",'".$date."','".$_POST['event_desc']."')");
}
//fin création evenement
//début modification points assos élèves
if (!empty($_POST['points']) && !empty($_POST['usertomodif'])){
	$connection->query('update pointsassos set notation='.$_POST['points'].' where id_user='.$_POST['usertomodif'].' and id_asso='.$_SESSION["association"]);
}
//fin modification points assos élèves
?>

<header class="header de page">
    
    <h1 id = "PagePresident"> <label> Gestion Présidence : </label> </h1></header>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" style="border:solid grey 1px;">
<fieldset>
  <label for="association">Gérer l'association : </label>
	<select id="association" name="association">
	<?php foreach ($assos_pres as $asso):?>
	<option value="<?php echo $asso->id_asso ?>" <?php if (!empty($_POST['association'])): if ($asso->id_asso == $_POST['association']): echo 'selected="selected"'; endif; endif; ?> ><?php echo $asso->name ?></option>
	<?php endforeach; ?>
	</select>
	<input type="submit" name="submit" value="Gérer"/>
</fieldset>
</form>

<?php if (!empty($_POST['association'])){
$_SESSION['association']=$_POST['association'];}
if (!empty($_SESSION['association'])):
$events=$connection->query('select * from events where id_asso='.$_SESSION['association'])->fetchAll(\PDO::FETCH_OBJ);
?>


<div class="gestion" id="gestion_evenements">

	<table class="table table-bordered table-hover table-striped">
		<caption>Récapitulatif des évènements</caption>
		<tr>
			<th>Evenement</th>
			<th>Date</th>
			<th>Description</th>
			<th>Coeff</th>
		</tr>
		<?php foreach ($events as $event) : ?>
		<tr>
			<form method="post">
			<td> <input type="text"  name="nameevent" class="tableinput" value="<?php echo $event->name ?>" /> </td>
			<td><input type="date"  name="dateevent" class="tableinput" value="<?php echo $event->date_ev ?>"></td>
			<td><input type="textarea"  name="descriptionevent" class="tableinput" value="<?php echo $event->description_event ?>"></td>
			<td><input type="text"  name="coeffevent" class="tableinput" value="<?php echo $event->coeff_event ?>"></td>
			<td class="actions">
				<input type="number" value="<?php echo $event->id_event ?>" name="idevent" class="idevent" readonly/>
				<input type="submit" name="submit" value="Modifier" />
				<input type="submit" name="suppr" value="Supprimer"/>
			</td>
			</form>

		</tr>
		<?php endforeach; ?>
	</table>
	

	<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
		<fieldset>
		<legend>Créer un évènement</legend>
		<p>
		<label for="event_name">Nom : </label>
		<input type="text" name="event_name" value=""/>
		</p>

		<p>
		<label for="event_date">Date : </label>
		<input type="date" name="event_date" value=""/>
		</p>

		<p>
		<label for="event_desc">Description : </label>
		<textarea class="desc" rows="5" cols="50" name="event_desc">Insérer la description</textarea>
		</p>
		<p>
		<label for="coeff_event">coefficient : </label>
		<input type="number" name="coeff_event" value="0" min="1" max="10"/>
		</p>
		<input type="submit" name="submit" value="Créer l'évènement"/>
		</fieldset>
	</form>
</div>

<div class="gestion" id="gestion_eleve">
<?php $eleves = $connection->query("select * from pointsassos_prop left join users using (id_user) where id_asso=".$_SESSION['association'])->fetchAll(\PDO::FETCH_OBJ);?>
	<table class="table table-bordered table-hover table-striped">
		<caption>Classement des élèves</caption>
		<tr>
			<th>Prénom</th>
			<th>Nom</th>
			<th>Pseudo</th>
			<th>Proposition</th>
			<th>moyenne</th>
		</tr>
		<?php foreach ($eleves as $eleve) : 
		$connection->query("insert into pointsassos (id_user,id_asso,notation,proposition) values (".$eleve->id_user.",".$_SESSION['association'].",".$eleve->moyenne.",".$eleve->moyenne.")");
$note = $connection->query("select * from pointsassos where id_user=".$eleve->id_user." and id_asso=".$_SESSION["association"])->fetch(\PDO::FETCH_OBJ);
		?>
		<tr>
			<form method="post">
			<td> <?php echo $eleve->firstname ?> </td>
			<td> <?php echo $eleve->lastname ?> </td>
			<td> <?php echo $eleve->pseudo ?> </td>
			<td> <?php echo $eleve->moyenne ?> </td>
			<td><input type="number" min="1" max="10" name="points" class="tableinput" value="<?php echo $note->notation ?>"></td>
			<td class="actions">
				<input type="number" value="<?php echo $eleve->id_user ?>" name="usertomodif" class="idevent" readonly/>
				<input type="submit" name="submit" value="Modifier" />
			</td>
			</form>

		</tr>
		<?php endforeach; ?>
	</table>
</div>

<div class="gestion" id="gestion_transmission">

</div>

<?php endif; ?>

</body>
</html>
