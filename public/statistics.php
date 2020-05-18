<?php
session_start();
require_once '..' . DIRECTORY_SEPARATOR . 'autoloader.php';
use controller\classes\Logger;
use environment\Environment;
use database\MySqlDatabasePDOConnection;
use controller\classes\TestStatisticsTables;
use controller\classes\Session;

Environment::initEnvironment();

?>
<!DOCTYPE html>
<html>
	<head>
        <meta charset="utf-8" />
        <meta name="description" content="Word management">
        <meta name="author" content="Balogh István">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="js/jquery.v.3.5.1.js" ></script>
        <script src="js/slidejs.js" ></script>
        <link type="text/css" rel="stylesheet" href="css/style.css" />
    	<title>Statisztikák</title>
	</head>
    <body>
    <div class="header">
        <div class="header-container">
            <div class="header-title">
            	<h1>magyar <-> angol szótár</h1>
            </div>
       		<?php include 'menu.php'; ?>
        </div>
    </div>
    <div class="container">
<?php

try{
  $conn = ( new MySqlDatabasePDOConnection())->getConnection();
  $printTestStatisticsTables = new TestStatisticsTables( $conn, Session::getUserNameSession() );
  
  echo '<div class="aggregated-table-div">';
  $printTestStatisticsTables->printAggregatedData();
  echo '</div>';
  echo '<div class="test-list-table-div">';
  $printTestStatisticsTables->printFilledOutTestsTable();
  echo '</div>';
  
}catch( PDOException $e ){
    Logger::log( $e->getCode() . ', ' . $e->getMessage() . ', ' . $e->getFile() . ', ' . $e->getLine() . ', ' . $e->getTraceAsString() );
}catch( Exception $e ){
    Logger::log( $e->getCode() . ', ' . $e->getMessage() . ', ' . $e->getFile() . ', ' . $e->getLine() . ', ' . $e->getTraceAsString() );
}catch( Error $e ){
    Logger::log( $e->getCode() . ', ' . $e->getMessage() . ', ' . $e->getFile() . ', ' . $e->getLine() . ', ' . $e->getTraceAsString() );
}finally{
    $conn = null;
}

?>
	</div> 
    </body>
</html>