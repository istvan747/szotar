<?php
    session_start();
    require_once '..' . DIRECTORY_SEPARATOR . 'autoloader.php';
    use environment\Environment;
    use controller\classes\Session;


    Environment::initEnvironment();
?>
<!DOCTYPE html>
<html>
	<head>
        <meta charset="utf-8" />
        <meta name="description" content="Dictionary program">
        <meta name="keywords" content="dictionary">
        <meta name="author" content="Balogh István">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link type="text/css" rel="stylesheet" href="css/style.css" />
        <script src="js/search.js" ></script>
        <script src="js/jquery.v.3.5.1.js" ></script>
        <script src="js/slidejs.js" ></script>
    	<title>Szótár</title>
	</head>
    <body>
    	<div class="header">
    		<div class="header-container">
        		<div class="header-title">
        			<h1>magyar <-> angol szótár</h1>
        		</div>
                <?php
                    if( !Session::isLoggedIn() ){
                        include 'login_form.php';
                    }else{
                        include 'menu.php';
                    }
                ?>
    		</div>
    	</div>
    	<div class="container">
    		<div class="search-div" >
                <?php include 'search_form.php'; ?>
                <div id="search_result" ></div>
            </div>
            <div class="registration-div" >
    		<?php 
                if( !Session::isLoggedIn() ){
                    include 'registration_form.php';
                }
    		 ?>
			</div>
		</div>
    </body>
</html>