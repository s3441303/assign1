<?php

if(isset($_GET['sessionStart'])){
  session_start();
  if (!array_key_exists('searchedWine',$_SESSION) && empty($_SESSION['searchedWine'])) {
     $_SESSION['searchedWine'] = array();
  }

}
if(isset($_GET['sessionEnd'])) {
  //echo "true";
  session_start();
  session_destroy();
  header("Location: index.php");
}

require_once('db.php');
require_once ("MiniTemplator.class.php");

try {
  $pdo = new PDO($dsn, DB_USER, DB_PW);

// all errors will throw exceptions
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  //$query = "SELECT * FROM wine where wine_name like '%". $wineName . "%'";

  $queryView = $pdo->prepare("CREATE TEMPORARY TABLE winestore AS SELECT wine.wine_name AS wine, 
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
  if (isset($_GET['wine']) && !empty($_GET['wine'])) {
  $wine = $_GET['wine'];
  array_push($_SESSION['searchedWine'], $_GET['wine']);
  $queryResult .= "AND wine like '%". $wine . "%' ";
}

if (isset($_GET['winery']) && !empty($_GET['winery'])) {
  $winery = $_GET['winery'];
  $queryResult .= "AND winery like '%". $winery . "%' ";

}

if (isset($_GET['region']) && !empty($_GET['region'])){
  if (trim($_GET['region']) == "All") {
    $region = "";
  }else{
    $region = $_GET['region'];
  }
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



//echo "$queryResult";
//session_destroy();

//echo "$queryResult";
$result = $pdo->query($queryResult, PDO::FETCH_ASSOC);


$t =  new MiniTemplator;
$ok = $t->readTemplateFromFile("result.html");
   if (!$ok) die ("MiniTemplator.readTemplateFromFile failed.");

$i = 0;
foreach ($result as $row) {
    $tweetMessage .= $row['wine']." ";
    foreach ($row as $cell) {
      $t->setVariable ("cell",$cell);
      $t->addBlock("Cell");
    }
  $i++;
  $t->setVariable("i",$i);
  $t->addBlock("Row");
}

foreach ($_SESSION['searchedWine'] as $searchedWine) {
  $t->setVariable ("searchedWine",$searchedWine);
  $t->addBlock("searchedWine");

}
$t->setVariable ("tweetMessage",$tweetMessage);
$t->generateOutput(); 

// if(isset($_GET['sessionEnd'])) {
//   echo "true";
//   session_destroy();
// }
//session_destroy();


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
