<?php
//check if user click to start session
if(isset($_GET['sessionStart'])){
  session_start();
  //creat a session array
  if (!array_key_exists('viewedWines',$_SESSION) && empty($_SESSION['viewedWines'])) {
     $_SESSION['viewedWines'] = array();
  }

}

//check if user click to end session
if(isset($_GET['sessionEnd'])) {
  session_start();
  session_destroy();
  header("Location: index.php");
}

// open database
require_once('db.php');
require_once ("MiniTemplator.class.php");

// create new template
$t =  new MiniTemplator;
$ok = $t->readTemplateFromFile("result.html");
   if (!$ok) die ("MiniTemplator.readTemplateFromFile failed.");

try {
  $pdo = new PDO($dsn, DB_USER, DB_PW);

// all errors will throw exceptions
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

 
// create a temporary table combining all the tables in the dabase
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

  // set a query from temporary table
  $queryResult = "SELECT * FROM winestore WHERE 1 ";

    // add conditions according to user input  
  if (isset($_GET['wine']) && !empty($_GET['wine'])) {
  $wine = $_GET['wine'];
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

if (!empty($_GET['yearFrom'])) {
  $yearFrom = $_GET['yearFrom'];
  $queryResult .= "AND year >=". $yearFrom . " ";

}

if (!empty($_GET['yearTo'])) {
  $yearTo = $_GET['yearTo'];
  $queryResult .= "AND year <=". $yearTo . " ";

}

if (!empty($_GET['minStock'])) {
  $minStock = $_GET['minStock'];
  $queryResult .= "AND stock >=". $minStock . " ";

}

if (!empty($_GET['minOrder'])) {
  $minOrder = $_GET['minOrder'];
  $queryResult .= "AND stock >=". $minOrder . " ";

}

if (!empty($_GET['minCost'])) {
  $minCost = $_GET['minCost'];
  $queryResult .= "AND cost >=". $minCost . " ";

}

if (!empty($_GET['maxCost'])) {
  $maxCost = $_GET['maxCost'];
  $queryResult .= "AND cost <=". $maxCost . " ";

}


// do the query
$result = $pdo->query($queryResult, PDO::FETCH_ASSOC);


//Check the number of rows that match query 
if ($result->rowCount() == 0) {
  echo "No result matched the query";
  exit;
}


$i = 0;
$searchedWineList = array();
foreach ($result as $row) {
    foreach ($row as $key => $cell) {
            switch ($key) {
        case 'wine':
          // add returned wine names to array variable
          array_push($searchedWineList, $cell);
          break;
        case 'cost':
          setlocale(LC_MONETARY, 'en_AU');
          $cell = money_format('%n', $cell);
          break;
        case 'sales':
          $cell = number_format($cell);
          break;
        case 'revenue':
          $cell = money_format('%n', $cell);
          break;

      }

      // if ($key == "wine") {
      // //echo $key. "=>". $cell;
      // array_push($searchedWineList, $cell);
      // }
      // if ($key == "cost") {
      //   setlocale(LC_MONETARY, 'en_AU');
      //   $cell = money_format('%i', $cell);
      // }
      $t->setVariable ("cell",$cell);
      $t->addBlock("Cell");
    }
    
    $i++;
  $t->setVariable("i",$i);
  $t->addBlock("Row");
}

//add wine name array variable to session array
array_push($_SESSION['viewedWines'], $searchedWineList);

$searchCount = count($_SESSION['viewedWines']) - 1;

for ($j=0; $j <= $searchCount; $j++) { 
      $t->setVariable("j",$j+1);
      $t->addBlock("searchCount");
}

$t->generateOutput(); 



  // close the connection by destroying the object
  $pdo = null;
} catch (PDOException $e) {
  echo $e->getMessage();
  exit;
}


?>
