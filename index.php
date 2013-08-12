<?php
require_once('db.php');
require_once ("MiniTemplator.class.php");

$t =  new MiniTemplator;
$ok = $t->readTemplateFromFile("form.html");
   if (!$ok) die ("MiniTemplator.readTemplateFromFile failed.");

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

