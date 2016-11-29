<?php
session_start();
include("db.php");
$_SESSION['page'] = "nodemap";
$db = new DBManager();
$result = NULL;

function make_row($row) {
	$output = "<tr>\n";
	$output .= sprintf("<td>%s</td>", $row["dust"]);
	$output .= sprintf("<td>%s</td>", $row["humidity"]);
	$output .= sprintf("<td>%s</td>", $row["temperature"]);
	$output .= sprintf("<td>%s</td>", $row["timestamp"]);
	$output .= sprintf("<td>%s</td>", $row["node_id"]);
	$output .= "</tr>\n";
	return $output;
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="leaflet/leaflet.css" rel="stylesheet">
		<link href="css/over.css" rel="stylesheet">
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="leaflet/leaflet.js"></script>
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<title>CDC SensorView</title>
	</head>
	<body>
	<?php include("nav.php"); ?>
	
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading"><h2>Node Map</h2></div>
			<div class="panel-body">
				<div id="map" style="height: 400px;">
				<script type="text/javascript">
				var map = L.map('map',{
				center: [43.64701, -79.39425],
				zoom: 15
				});
				L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
				}).addTo(map);
				L.marker([43.64701, -79.39425]).addTo(map);
				</script>
				</div>
			</div>
		</div>

	</div>
	</body>
</html>