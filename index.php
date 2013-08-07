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

  echo $queryWineYear;
  echo $queryGrapeVariety;


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
								<input type="text" class="form-control" id="wineName" name="wineName" placeholder="Enter wine name">	
							</div>
							<div class="form-group">
								<label for="wineryName">Winery Name</label>
								<input type="text" class="form-control" id="wineryName" placeholder="Enter winery name">	
							</div>
							<div class="form-group">
								<label for="wineRegion">Winery Region</label>
								<select class="form-control" id="wineRegion">
								  <option>Select a region</option>
								 <?php
										foreach ($resultWineryRegion as $row) {
											 echo '<option>'.$row[region_name].'</option>';
										}
								  ?>
								</select>
							</div>
							<div class="form-group">
								<label for="grapeVariety">Grape Rariety</label>
								<select class="form-control" id="grapeVariety">
								  <option>Select a grape variety</option>
								  <?php
										foreach ($resultGrapeVariety as $row) {
											 echo '<option>'.$row[variety].'</option>';
										}
								  ?>
								</select>
							</div>
							<div class="form-group">
								<label>Year Range</label>
								<div class="row">
									<div class="col-lg-3">
										<select class="form-control" >
										  <option>From</option>
										  <?php
										    foreach ($resultWineYear as $row) {
											    echo '<option>'.$row[0].'</option>';
											}
										  ?>
										</select>
									</div>
									<div class="col-lg-3">
										<select class="form-control">
										  <option>To</option>
										  <?php
										    foreach ($resultWineYear2 as $row) {
											    echo '<option>'.$row[0].'</option>';
											}
										  ?>
										  <option>5</option>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label>Cost Range</label>
								<div class="row">
									<div class="col-lg-3">
										<select class="form-control">
										  <option>Lower bound</option>
										  <option>2</option>
										  <option>3</option>
										  <option>4</option>
										  <option>5</option>
										</select>
									</div>
									<div class="col-lg-3">
										<select class="form-control">
										  <option>Upper bound</option>
										  <option>2</option>
										  <option>3</option>
										  <option>4</option>
										  <option>5</option>
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
