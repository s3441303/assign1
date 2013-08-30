<?php
session_start();
$index = intval($_GET['id'])-1;

foreach ($_SESSION['viewedWines'][$index] as $key=>$viewedWines) {
        echo '<tr><td>'.$viewedWines.'</td></tr>';
    }

?>