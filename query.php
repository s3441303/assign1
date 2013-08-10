<?php

require_once('db.php');


try {
  $pdo = new PDO($dsn, DB_USER, DB_PW);

// all errors will throw exceptions
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  //$query = "SELECT * FROM wine where wine_name like '%". $wineName . "%'";

  $query = "SELECT wine.wine_id, wine.wine_name AS wine, wine.year,winery.winery_name AS winery,
                    region.region_name AS region,grape_variety.variety,inventory.cost AS cost,
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
                  ORDER BY wine.wine_id asc";

  //echo $query;  
  if (isset($_GET['wineName'])) {
  $wineName = $_GET['wineName'];
}

if (isset($_GET['wineName'])) {
  $wineName = $_GET['wineName'];
}
  //die;
  //$result = $pdo->query($query);

 // also try PDO::FETCH_NUM, PDO::FETCH_ASSOC and PDO::FETCH_BOTH
 $result = $pdo->query($query, PDO::FETCH_ASSOC);

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