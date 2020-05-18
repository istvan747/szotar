<?php 
session_start();

require_once '..' . DIRECTORY_SEPARATOR . 'autoloader.php';

use controller\classes\Session;

Session::logOutSession();

$host  = $_SERVER['HTTP_HOST'];

header("Location: http://$host/");
exit;


?>