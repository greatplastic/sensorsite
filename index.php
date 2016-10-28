<?php
session_start();
include("db.php");
include("search_params.php");
$_SESSION['page'] = "table";
$db = new DBManager();

// Update search parameters
if (isset($_POST["search"])) {
	$sp = new SearchParameters();
	foreach($_POST["search"] as $parameter) {
		switch($parameter) {
			case "time":
				$sp->set_time_params($_POST["from_time_input"], $_POST["to_time_input"]);
				break;
			case "dust":
				$sp->set_dust_params($_POST["from_dust_input"], $_POST["to_dust_input"]);
				break;
			case "node":
				$sp->set_node_params($_POST["node_input"]);
				break;
		}
	}
	
	// Send search parameters to DBmanager
}

if (isset($_POST["from_time_input"])) {
	echo($_POST["from_time_input"]);
}

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
		<link href="css/bootstrap-datetimepicker.css" rel="stylesheet">
		<link href="css/over.css" rel="stylesheet">
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/moment-with-locales.js"></script>
		<script src="js/bootstrap-datetimepicker.js"></script>
		<script type="text/javascript">
			$(function () {
				$('#from_time').datetimepicker();
			});
			$(function () {
				$('#to_time').datetimepicker();
			});
		</script>
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
			<div class="panel-body">
				<form action="index.php" method="post">
					<div class="btn-group" data-toggle="buttons">
					<label class="btn btn-default active">
						<input type="checkbox" checked="checked" autocomplete="off" name="search[]" value="time"> Time Range
					</label>
					<label class="btn btn-default">
						<input type="checkbox" autocomplete="off" name="search[]" value="dust"> Dust Level
					</label>
					<label class="btn btn-default">
						<input type="checkbox" autocomplete="off" name="search[]" value="node"> Node ID
					</label>
					</div>
					<br><br>
					
					<strong>Time Range</strong>
					<br>
					From:         
					<div class='input-group date' id='from_time'>
						<input type='text' name="from_time_input" class="form-control" />
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
					To: 
					<div class='input-group date' id='to_time'>
						<input type='text' name="to_time_input" class="form-control"/>
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
					<br>
					
					<strong>Dust Range</strong>
					<br>
					From:
					<input type='text' name="from_dust_input" class="form-control" />
					To:
					<input type='text' name="to_dust_input" class="form-control" />
					<br>
					
					<strong>Node ID</strong>
					<input type='text' name="node_input" class="form-control" />
					<br>
					
					<button type="submit" class="btn btn-default btn-lg">Submit</button>
				</form>
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading"><h2>Results</h2></div>
			<div class="panel-body">
				<table class="table table-striped">
				<thead>
					<tr>
						<th>Dust</th>
						<th>Humidity</th>
						<th>Temperature</th>
						<th>Timestamp</th>
						<th>Node ID</th>
					</tr>
				</thead>
				<tbody>
					<?php 
/* 					if ($result = $db_con->query("SELECT * FROM data LIMIT 20")) {
						while ($curr_row = $result->fetch_assoc()) {
							echo make_row($curr_row);
						}
						$result->free();
					} */
					?>
				</tbody>
				</table>
			</div>
		</div>
	</div>
	</body>
</html>