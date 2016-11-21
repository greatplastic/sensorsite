<?php
session_start();
date_default_timezone_set('America/New_York'); // EST for date conversions
$_SESSION['page'] = "graph";
include("db.php");
include("search_params.php");
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_line.php');
require_once ('jpgraph/jpgraph_error.php');
$db = new DBManager();
$result = NULL;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$sp = new SearchParameters();
	$sp->set_time_params($_POST["from_time_input"], $_POST["to_time_input"]);
	$sp->set_node_params($_POST["node_input"]);
	var_dump($sp);
	$result = $db->execute($sp);
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
$datay1 = array(20,15,23,15);
$datay2 = array(12,9,42,8);
$datay3 = array(5,17,32,24);

// Setup the graph
$graph = new Graph(800,600);
$graph->SetScale("textlin");

$theme_class=new UniversalTheme;

$graph->SetTheme($theme_class);
$graph->img->SetAntiAliasing(false);
$graph->title->Set('Filled Y-grid');
$graph->SetBox(false);

$graph->img->SetAntiAliasing();

$graph->yaxis->HideZeroLabel();
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);

$graph->xgrid->Show();
$graph->xgrid->SetLineStyle("solid");
$graph->xaxis->SetTickLabels(array('A','B','C','D'));
$graph->xgrid->SetColor('#E3E3E3');

// Create the first line
$p1 = new LinePlot($datay1);
$graph->Add($p1);
$p1->SetColor("#6495ED");
$p1->SetLegend('Line 1');

// Create the second line
$p2 = new LinePlot($datay2);
$graph->Add($p2);
$p2->SetColor("#B22222");
$p2->SetLegend('Line 2');

// Create the third line
$p3 = new LinePlot($datay3);
$graph->Add($p3);
$p3->SetColor("#FF1493");
$p3->SetLegend('Line 3');

$graph->legend->SetFrameWeight(1);

// Output line
@unlink("test.jpg");
$graph->Stroke("test.jpg");
?>
		<div class="panel panel-default">
			<div class="panel-heading"><h2>Results</h2></div>
			<div class="panel-body">
			<img src="test.jpg"></img>
			</div>
		</div>
	</div>
	</body>
</html>