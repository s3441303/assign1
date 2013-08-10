<?php
require_once('db.php');

try {
  $pdo = new PDO($dsn, DB_USER, DB_PW);

// all errors will throw exceptions
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $queryWineryRegion = 'SELECT DISTINCT region_name FROM region ORDER BY region_name asc';
  $queryWineYear = 'SELECT DISTINCT year FROM wine ORDER BY year asc';
  $queryGrapeVariety = 'SELECT DISTINCT variety FROM grape_variety ORDER BY variety asc';
  //$queryCost = 'SELECT DISTINCT '

  //echo $queryWineYear;
  //echo $queryGrapeVariety;


 // also try PDO::FETCH_NUM, PDO::FETCH_ASSOC and PDO::FETCH_BOTH
  $resultWineYear = $pdo->query($queryWineYear, PDO::FETCH_BOTH);
  $resultGrapeVariety = $pdo->query($queryGrapeVariety, PDO::FETCH_BOTH);
  $resultWineryRegion = $pdo->query($queryWineryRegion, PDO::FETCH_BOTH);
  $resultWineYear2 = $pdo->query($queryWineYear, PDO::FETCH_BOTH);

  // echo '<pre>';
  // print_r($resultYear);
  // echo '</pre>';

  // foreach ($resultYear as $row) {
  //   echo '<pre>';
  //   print_r($row[year]);
  //   echo '</pre>';
  // }

  // close the connection by destroying the object
  $pdo = null;
} catch (PDOException $e) {
  echo $e->getMessage();
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<title>Winestore Database System</title>
		<meta name="viewport" content="width=device-width; initial-scale=1.0" />
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		
	</head>

	<body>
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-lg-offset-3">
					<h1>Winestore Database System</h1>
					<br />
					<form class="well" action="query.php" method="GET">
						<fieldset>
							<legend>Please one or more criterias to search.</legend>
							<div class="form-group">
								<label for="wineName">Wine Name</label>
								<input type="text" class="form-control" id="wineName" name="wine" placeholder="Enter wine name">	
							</div>
							<div class="form-group">
								<label for="wineryName">Winery Name</label>
								<input type="text" class="form-control" id="wineryName" name="winery"placeholder="Enter winery name">	
							</div>
							<div class="form-group">
								<label for="wineRegion">Winery Region</label>
								<select class="form-control" id="wineRegion" name="region">
								  <option value="">Select a region</option>
								 <?php
										foreach ($resultWineryRegion as $row) {
											 echo '<option value="'.$row[region_name].'">'.$row[region_name].'</option>';
										}
								  ?>
								</select>
							</div>
							<div class="form-group">
								<label for="grapeVariety">Grape Variety</label>
								<select class="form-control" id="grapeVariety" name="variety">
								  <option value="">Select a grape variety</option>
								  <?php
										foreach ($resultGrapeVariety as $row) {
											 echo '<option value"'.$row[variety].'">'.$row[variety].'</option>';
										}
								  ?>
								</select>
							</div>
							<div class="form-group">
								<label>Year Range</label>
								<div class="row">
									<div class="col-lg-3">
										<select class="form-control" id="yearFrom" name="from" >
										  <option value="">From</option>
										  <?php
										    foreach ($resultWineYear as $from) {
											    echo '<option value="'.$from[year].'">'.$from[year].'</option>';
											}
										  ?>
										</select>
									</div>
									<div class="col-lg-3">
										<select class="form-control" id="yearTo" name="to">
										  <option value="">To</option>
										  <?php
										    foreach ($resultWineYear2 as $to) {
											    echo '<option value="'.$to[year].'">'.$to[year].'</option>';
											}
										  ?>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">
                            	<label for="minStock">A minimum number of wines in stock, per wine</label>
                            	<div class="row">
 									<div class="col-lg-3">
 										<input type="number" class="form-control" id="minStock" name="minStock" min="0">
 									</div>
                            	</div>
							</div>
							<div class="form-group">
                            	<label for="minOrder">A minimum number of wines ordered, per wine</label>
                            	<div class="row">
 									<div class="col-lg-3">
 										<input type="number" class="form-control" id="minOrder" name="minOrder" min="0">
 									</div>
                            	</div>
							</div>
							<div class="form-group">
								<label>Cost Range (Per Wine)</label>
								<div class="row">
									<div class="col-lg-3">
										<select class="form-control" name="min">
										  <option value="">Min</option>
										  <option value="5">5</option>
										  <option value="10">10</option>
										  <option value="15">15</option>
										  <option value="20">20</option>
										  <option value="25">25</option>
										  <option value="30">30</option>
										</select>
									</div>
									<div class="col-lg-3">
										<select class="form-control" name="max">
										  <option value="">Max</option>
										  <option value="5">5</option>
										  <option value="10">10</option>
										  <option value="15">15</option>
										  <option value="20">20</option>
										  <option value="25">25</option>
										  <option value="30">30</option>
										</select>
									</div>
								</div>
							</div>
							<div class="pull-right">
								<button type="submit" class="btn btn-success btn-lg">Search</button>
								<button type="button" class="btn btn-danger btn-lg">Clear</button>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
			
			<footer class="col-lg-6 col-lg-offset-3">
				<p>
					&copy; Copyright  by Lixing Zhang, Student ID: s344103
				</p>
			</footer>
		</div>
	</body>
	<script src="http://code.jquery.com/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
</html>
