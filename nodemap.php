<?php
session_start();
include("db.php");
$_SESSION['page'] = "nodemap";
$db = new DBManager();
$result = $db->get_node_locs();
function make_row($row) {
	$script = "map.panTo(new L.LatLng($row[latitude], $row[longitude]));";
	$output = "<tr>\n";
	$output .= sprintf("<td style='cursor: pointer' onclick='$script'>%s &nbsp;&nbsp;&nbsp;&nbsp;(click to view on map)</td>", $row["node_id"]);
	$output .= sprintf("<td>%s</td>", $row["latitude"]);
	$output .= sprintf("<td>%s</td>", $row["longitude"]);
	$output .= sprintf("<td><button type='submit' onclick='return confirm(\"Trigger node %s?\")' name=submit value=%s class='btn btn-default btn-xs'>Trigger</button></td>", $row["node_id"], $row["node_id"]);
	$output .= "</tr>\n";
	return $output;
}

function add_markers($lats, $longs) {
	for ($i=0; $i<count($lats); $i++) {
		echo "L.marker([$lats[$i], $longs[$i]]).addTo(map);";
	}
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
	<?php include("nav.php"); 
	if (isset($_POST["submit"])) {
		$triggered = $db->trigger_node($_POST["submit"]);
		$node = (int) $_POST["submit"];
		echo '<script language="javascript">';
		if ($triggered) {
			echo "alert('Node $node successfully triggered.')";
		} else {
			echo "alert('Node $node could not be triggered!')";
		}
		echo '</script>';
	}
	?>
	
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading"><h2>Node Map</h2></div>
			<div class="panel-body">
				<form action="nodemap.php" method="post">
				<table class="table table-striped">
				<thead>
					<tr>
						<th>Node ID</th>
						<th>Latitude</th>
						<th>Longitude</th>
						<th>Trigger Node?</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$lats = array();
					$longs = array();
 					if (!is_null($result) && $result != false) {
						while ($curr_row = $result->fetch_assoc()) {
							$lats[] = $curr_row["latitude"];
							$longs[] = $curr_row["longitude"];
							echo make_row($curr_row);
						}
						$result->free();
					} 
					?>
				</tbody>
				</table>
				</form>				
				<div id="map" style="height: 400px;">
				<script type="text/javascript">
				var map = L.map('map',{
				center: [33.7983632, -84.3272197],
				zoom: 10
				});
				L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
				}).addTo(map);
				<?php add_markers($lats, $longs); ?>
				</script>
				</div>
			</div>
		</div>

	</div>
	</body>
</html>