<?php
session_start();
function is_active_page($page_name) {
	if ($_SESSION['page'] == $page_name) {
		echo " class=\"active\"";
	} else {
		echo "";
	}
}
?>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	  <a class="navbar-brand" href="index.php">CDC SensorView</a>
	</div>
	<div id="navbar" class="navbar-collapse collapse">
		<ul class="nav navbar-nav navbar-right">
			<li <?php is_active_page("table"); ?>><a href="index.php">Table View</a></li>
			<li <?php is_active_page("graph"); ?>><a href="graph.php">Graph View</a></li>
			<li <?php is_active_page("nodemap"); ?>><a href="nodemap.php">Node Map</a></li>
		</ul>
	</div>
  </div>
</nav>