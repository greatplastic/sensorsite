<?php
session_start();
$_SESSION['page'] = "table";
include("db.php");

function make_row($doc) {
	$row = "<tr>\n";
	$row .= "<td>" . $doc["devid"] . "</td>";
	$row .= "<td>" . $doc["temp"] . "</td>";
	$row .= "<td>" . $doc["time"] . "</td>\n";
	$row .= "</tr>\n";
	return $row;
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/over.css" rel="stylesheet">
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
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
			<div class="panel-heading"><h2>Search Parameters</h2></div>
			<div class="panel-body">Panel Content</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading"><h2>Results</h2></div>
			<div class="panel-body">
				<table class="table table-striped">
				<thead>
					<tr>
						<th>Device ID</th>
						<th>Temp</th>
						<th>Time</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$cursor = $nodeinfo->find();
					foreach($cursor as $doc) {
						echo make_row($doc);
					}
					?>
				</tbody>
				</table>
			</div>
		</div>
	</div>
	</body>
</html>