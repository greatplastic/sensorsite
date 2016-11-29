<?php
session_start();
include("db.php");
include("search_params.php");
date_default_timezone_set('America/New_York'); // EST for date conversions
$_SESSION['page'] = "graph";
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_line.php');
require_once ('jpgraph/jpgraph_date.php');
require_once ('jpgraph/jpgraph_error.php');
require_once ('jpgraph/jpgraph_utils.inc.php');
$db = new DBManager();
$result = NULL;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$sp = new SearchParameters();
	$sp->set_time_params($_POST["from_time_input"], $_POST["to_time_input"]);
	$sp->set_node_params($_POST["node_input"]);
	$result = $db->execute($sp);
}

/* Time intervals used for graphing 
 with a sampling rate of 12 seconds */
$sec_interval = 5; // 5 samples in 1 minute
$min_interval = 300; // 300 samples in 1 hour
$hour_interval = 7200; // 7200 samples in 1 day
$day_interval = 50400; // 50400 samples in 1 week
$week_interval = 201600; // 201600 samples in 28 days
$month_interval = 2419200; // 2419200 samples in 12*28 days

function make_base_graph($title, $time) {
	$graph = new Graph(800,600);
	$graph->SetScale("datint");
	$theme_class = new UniversalTheme;
	$graph->SetTheme($theme_class);
	$graph->img->SetAntiAliasing(false);
	$graph->title->Set($title);
	$graph->SetBox(false);
	$graph->img->SetAntiAliasing();
	
	$graph->yaxis->HideZeroLabel();
	$graph->yaxis->HideLine(false);
	$graph->yaxis->HideTicks(false,false);
		
	$graph->xgrid->Show();
	$graph->xgrid->SetLineStyle("solid");
	$graph->xaxis->SetLabelAngle(80);
	$graph->xgrid->SetColor('#E3E3E3');
	
	$samples = count($time);
	if ($samples <= $sec_interval) {
		$graph ->xaxis->scale->SetDateFormat('i:s');
		//$graph->xaxis->SetTextLabelInterval(2);
	} elseif ($samples <= $min_interval) {
		$graph ->xaxis->scale->SetDateFormat('H:i');
		//$graph->xaxis->SetTextLabelInterval(2);
	} elseif ($samples <= $hour_interval) {
		$graph ->xaxis->scale->SetDateFormat('d H:i');
		//$graph->xaxis->SetTextLabelInterval(2);
	} elseif ($samples <= $day_interval) {
		$graph ->xaxis->scale->SetDateFormat('d H:i');
		//$graph->xaxis->SetTextLabelInterval(2);
	} elseif ($samples <= $week_interval) {
		$graph ->xaxis->scale->SetDateFormat('m-d H:i:s');
		//$graph->xaxis->SetTextLabelInterval(2);
	} else {
		$graph ->xaxis->scale->SetDateFormat('Y-m-d H:i:s');
		//$graph->xaxis->SetTextLabelInterval(2);
	}
	$graph->xaxis->SetTickLabels($time);
	return $graph;
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
				$('#from_time').datetimepicker({
					format: 'YYYY-MM-DD HH:mm:ss'
				});
			});
			$(function () {
				$('#to_time').datetimepicker({
					format: 'YYYY-MM-DD HH:mm:ss'
				});
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
				<form action="graph.php" method="post">		
					<div id="time_options">
					<strong>Time Range</strong>
					<br>
					From:         
					<div class='input-group date' id='from_time'>
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
						<input type='text' placeholder="YYYY-MM-DD HH:mm:ss or leave blank for earliest time" name="from_time_input" class="form-control" />
					</div>
					To: 
					<div class='input-group date' id='to_time'>
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
						<input type='text' placeholder="YYYY-MM-DD HH:mm:ss or leave blank for current time" name="to_time_input" class="form-control"/>
					</div>
					<br>
					</div>
					
					<div id="node_options">
					<strong>Node ID</strong>
					<input type='text' name="node_input" class="form-control" />
					<br>
					</div>
					<button type="submit" class="btn btn-default btn-lg">Submit</button>
				</form>
			</div>
		</div>
		<?php
		$node_id = 0;
		$time_data = array();
		$temp_data = array();
		$hum_data = array();
		$dust_data = array();
		if (!is_null($result)) {
			while ($curr_row = $result->fetch_assoc()) {
				$time_data[] = date("Y-m-d H:i:s", strtotime($curr_row["timestamp"]));
				$temp_data[] = $curr_row["temperature"];
				$hum_data[] = $curr_row["humidity"];
				$dust_data[] = $curr_row["dust"];
				$node_id = $curr_row["node_id"];
			}
			$result->free();
			$temp_graph = make_base_graph("Node $node_id Temperature", $time_data);
			$hum_graph = make_base_graph("Node $node_id Humidity", $time_data);
			$dust_graph = make_base_graph("Node $node_id Dust", $time_data);
			
			// Filter data length here
			

			$p1 = new LinePlot($temp_data);
			$temp_graph->Add($p1);
			$p1->SetColor("#6495ED");
			$p1->SetLegend('Temperature');

			$p2 = new LinePlot($hum_data);
			$hum_graph->Add($p2);
			$p2->SetColor("#B22222");
			$p2->SetLegend('Humidity');

			$p3 = new LinePlot($dust_data);
			$dust_graph->Add($p3);
			$p3->SetColor("#FF1493");
			$p3->SetLegend('Dust');

			$temp_graph->legend->SetFrameWeight(1);
			$hum_graph->legend->SetFrameWeight(1);
			$dust_graph->legend->SetFrameWeight(1);

			// Output line
			@unlink("temp_graph.jpg");
			@unlink("hum_graph.jpg");
			@unlink("dust_graph.jpg");
			$temp_graph->Stroke("temp_graph.jpg");
			$hum_graph->Stroke("hum_graph.jpg");
			$dust_graph->Stroke("dust_graph.jpg");
		}
		?>
		<div class="panel panel-default">
			<div class="panel-heading"><h2>Results</h2></div>
			<div class="panel-body">
			<?php
			if (!is_null($result)) {
				echo '<center>';
				echo '<img src="temp_graph.jpg"></img><br>';
				echo '<img src="hum_graph.jpg"></img><br>';
				echo '<img src="dust_graph.jpg"></img><br>';
				echo '</center>';
			}
			?>
			</div>
		</div>
	</div>
	</body>
</html>