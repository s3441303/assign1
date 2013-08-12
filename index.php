<?php
require_once('db.php');
require_once ("MiniTemplator.class.php");

$tpl =  new MiniTemplator;
$ok = $tpl->readTemplateFromFile("form.html");
   if (!$ok) die ("MiniTemplator.readTemplateFromFile failed.");

try {
  $pdo = new PDO($dsn, DB_USER, DB_PW);

// all errors will throw exceptions
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $queryWineryRegion = 'SELECT DISTINCT region_name AS region FROM region ORDER BY region_name asc';
  $queryWineYear = 'SELECT DISTINCT year FROM wine ORDER BY year asc';
  $queryGrapeVariety = 'SELECT DISTINCT variety FROM grape_variety ORDER BY variety asc';
  //$queryCost = 'SELECT DISTINCT '

  //echo $queryWineYear;
  //echo $queryGrapeVariety;


 // also try PDO::FETCH_NUM, PDO::FETCH_ASSOC and PDO::FETCH_BOTH
  $resultWineYearFrom = $pdo->query($queryWineYear, PDO::FETCH_ASSOC);
  $resultGrapeVariety = $pdo->query($queryGrapeVariety, PDO::FETCH_ASSOC);
  $resultWineryRegion = $pdo->query($queryWineryRegion, PDO::FETCH_ASSOC);
  $resultWineYearTo = $pdo->query($queryWineYear, PDO::FETCH_ASSOC);

  // echo '<pre>';
  // print_r($resultYear);
  // echo '</pre>';

  // foreach ($resultWineryRegion as $row) {
  //   echo '<pre>';
  //   print_r($row);
  //   echo '</pre>';
  // }

  //Print_r($resultWineryRegion);
  //echo blockExists(Region);
  //echo variableExists($region）;

  foreach ($resultWineryRegion as $row) {
    foreach ($row as $region) {
      $tpl->setVariable ("region",$region);  
      $tpl->addBlock("Region");
    }
  }

  foreach ($resultGrapeVariety as $row) {
    foreach ($row as $variety) {
      $tpl->setVariable ("variety",$variety);  
      $tpl->addBlock("Variety");
    }
  }
  
  foreach ($resultWineYearFrom as $row) {
    foreach ($row as $yearFrom) {
      $tpl->setVariable ("yearFrom",$yearFrom);  
      $tpl->addBlock("yearFrom");
    }
  }

  foreach ($resultWineYearTo as $row) {
    foreach ($row as $yearTo) {
      $tpl->setVariable ("yearTo",$yearTo);  
      $tpl->addBlock("yearTo");
    }
  }



$tpl->generateOutput(); 

  // close the connection by destroying the object
  $pdo = null;
} catch (PDOException $e) {
  echo $e->getMessage();
  exit;
}
?>

