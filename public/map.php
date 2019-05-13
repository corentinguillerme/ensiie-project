<?php include ('view.php');
 $ville = $_GET['ville'];
 session_start();
?>

<html>
	<head>
		<meta charset="utf-8">
		<?php my_head(); ?>
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css"
   integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
   crossorigin=""/>
	</head>

	<body>
		<?php header_login(); ?>
		<div id="search">
		     <form action="map.php">
		      	   <span style="font-size:140%">Trouve le spot le plus près de chez toi :</br></span>
		      	   <input id="searchbar" type="text" name="ville" placeholder="Entrez votre ville">
		       </form>
		</div>
		<div id="mapid"></div>
		<script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"
   integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og=="
   crossorigin=""></script>
		<script type="text/javascript" src="app.js"></script>
		<?php
			foreach ($spots as $spot) :
				$lat = $spot->getlatitude;
				echo $lat;
				$long = $spot->getLongitude;
				echo $long;
			endforeach;
		?>
	<footer>
		<?php footer();?>
	</footer>
	</body>
</html>

<script>
	
</script>
