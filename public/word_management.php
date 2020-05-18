<?php
session_start();
require_once '..' . DIRECTORY_SEPARATOR . 'autoloader.php';

use environment\Environment;
use controller\classes\Session;

$host = $_SERVER['HTTP_HOST'];

Environment::initEnvironment();

if( !Session::isLoggedIn() ){
    header("Location: http://$host/");
    exit;
}

if( !Session::isAdmin() )
{
    Session::logOutSession();
    header("Location: http://$host/");
    exit;
}

?>
<!DOCTYPE html>
<html>
	<head>
        <meta charset="utf-8" />
        <meta name="description" content="Word management">
        <meta name="author" content="Balogh István">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link type="text/css" rel="stylesheet" href="css/style.css" />
        <script src="js/jquery.v.3.5.1.js" ></script>
        <script src="js/slidejs.js" ></script>        
    	<title>Szavak kezelése</title>
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
        	<div class="word-management" >
        		<form name="add_word" method="post" action="add_word" >
        			<table>
						<tr class="language-row" >
        					<td><input type="radio" name="language" id="magyar_radio" value="magyar-angol" checked /></td>
        					<td></td>
        					<td><label for="magyar_radio">magyar - angol</label></td>
        				</tr>
						<tr class="language-row">
        					<td><input type="radio" name="language" id="angol_radio" value="angol-magyar" /></td>
        					<td></td>
        					<td><label for="angol_radio">angol - magyar</label></td>
        				</tr>
        				<tr class="word-row">
        					<td><input type="text" name="wordA" value="" /></td>
        					<td>-</td>
        					<td><input type="text" name="wordB" value="" /></td>
        				</tr>
        				<tr class="topic-row">
        					<td><label for="topic">témakör</label></td>
        					<td>:</td>
        					<td><input type="text" name="topic" id="topic" value="" /></td>
        				</tr>
        				<tr class="word-class-row">
        					<td><label for="word_class" >szófaj</label></td>
        					<td>:</td>
        					<td><input type="text" name="word_class" id="word_class" value="" /></td>
        				</tr>
        				<tr class="word-add-button-row" >
        					<td colspan="3">
        						<input type="submit" name="word_add_submit_button" value="ment" />
        					</td>
        				</tr>
        			</table>

        		</form>
    		</div>
		</div>
    </body>
</html>