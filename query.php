<?php

require_once('db.php');


try {
  $pdo = new PDO($dsn, DB_USER, DB_PW);

// all errors will throw exceptions
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  //$query = "SELECT * FROM wine where wine_name like '%". $wineName . "%'";

  $queryView = $pdo->prepare("CREATE TEMPORARY TABLE winestore AS SELECT wine.wine_id, wine.wine_name AS wine, 
                    wine.year AS year,winery.winery_name AS winery, region.region_name AS region,
                    grape_variety.variety AS variety,inventory.cost AS cost,
                    inventory.on_hand AS stock,
                    SUM(items.qty) AS sales, SUM(items.qty * items.price) AS revenue
            FROM wine,winery,region,grape_variety,wine_variety,inventory,items 
            WHERE wine.winery_id = winery.winery_id 
                  AND winery.region_id = region.region_id
                  AND wine.wine_id = wine_variety.wine_id
                  AND wine_variety.variety_id = grape_variety.variety_id
                  AND wine.wine_id = inventory.wine_id
                  AND wine.wine_id = items.wine_id
                  GROUP BY wine.wine_id
                  ORDER BY wine.wine_id asc");

  $queryView->execute();

  $queryResult = "SELECT * FROM winestore WHERE 1 ";

  //echo $query;  
  if (!empty($_GET['wine'])) {
  $wine = $_GET['wine'];
  $queryResult .= "AND wine like '%". $wine . "%' ";
}

if (!empty($_GET['winery'])) {
  $winery = $_GET['winery'];
  $queryResult .= "AND winery like '%". $winery . "%' ";

}

if (!empty($_GET['region'])) {
  $region = $_GET['region'];
  $queryResult .= "AND region like '%". $region . "%' ";

}

if (!empty($_GET['variety'])) {
  $variety = $_GET['variety'];
  $queryResult .= "AND variety like '%". $variety . "%' ";

}

if (!empty($_GET['from'])) {
  $from = $_GET['from'];
  $queryResult .= "AND year >=". $from . " ";

}

if (!empty($_GET['to'])) {
  $to = $_GET['to'];
  $queryResult .= "AND year <=". $to . " ";

}

if (!empty($_GET['minStock'])) {
  $minStock = $_GET['minStock'];
  $queryResult .= "AND stock >=". $minStock . " ";

}

if (!empty($_GET['minOrder'])) {
  $minOrder = $_GET['minOrder'];
  $queryResult .= "AND stock >=". $minOrder . " ";

}

if (!empty($_GET['min'])) {
  $min = $_GET['min'];
  $queryResult .= "AND cost >=". $min . " ";

}

if (!empty($_GET['max'])) {
  $max = $_GET['max'];
  $queryResult .= "AND cost <=". $max . " ";

}

echo "$queryResult";

//echo "$queryResult";
$result = $pdo->query($queryResult, PDO::FETCH_ASSOC);

  //die;
  //$result = $pdo->query($query);

 // also try PDO::FETCH_NUM, PDO::FETCH_ASSOC and PDO::FETCH_BOTH

  // echo '<pre>';
  // print_r($result);
  // echo '</pre>';

  // foreach ($result as $row) {
  //   echo '<pre>';
  //   print_r($row);
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
        <h3>Search Result</h3>
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Wine id</th>
              <th>Wine Name</th>
              <th>year</th>
              <th>Winery Name</th>
              <th>Region</th>
              <th>Grape Variety</th>
              <th>Cost (dol/btl)</th>
              <th>Stock in Total (btl)</th>
              <th>Sales in Total (btl)</th>
              <th>Total Sales Revenue (dol)</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $i = 1;
              foreach ($result as $row) {
                  echo '<tr>';
                  echo '<td>'.$i.'</td>';
                foreach ($row as $cell) {
                  echo '<td>'.$cell.'</td>';
                }
                  echo '</tr>';
              $i++;
              }     
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </body>
</html>