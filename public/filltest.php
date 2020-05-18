<?php
session_start();
require_once '..' . DIRECTORY_SEPARATOR . 'autoloader.php';
use environment\Environment;
use controller\classes\Session;
use controller\classes\TopicGroupSelectionField;

Environment::initEnvironment();

if( !Session::isLoggedIn() ){
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
    	<title>Teszt</title>
        <link type="text/css" rel="stylesheet" href="css/style.css" />
        <script src="js/topicSelectionDisable.js" ></script>
        <script src="js/jquery.v.3.5.1.js" ></script>
        <script src="js/test.js" ></script>
        <script src="js/slidejs.js" ></script>
	</head>
    <body onload="initRadio()">
        <div class="header">
            <div class="header-container">
                <div class="header-title">
                	<h1>magyar <-> angol szótár</h1>
                </div>
                <?php include 'menu.php'; ?>
            </div>
        </div>
        <div class="container" >
            <div class="test_settings">
        		<form name="test_settings" method="get" action="" >
        			<div class="test-settings-radio-buttons">
            			<div class="test-language-div">
            				<div>
                            	<input type="radio" name="test_language" id="magyar_angol" value="magyar-angol" checked /><label for="magyar_angol"> magyar - angol </label>
                            </div>
                            <div>             
                            	<input type="radio" name="test_language" id="angol_magyar" value="angol-magyar" /><label for="angol_magyar"> angol - magyar </label>
                            </div>
                        </div>
                        <div class="test-type-div">
                            <div>
                            	<input type="radio" name="test_variety" id="least_frequently_asked_words" value="least_frequently_asked_words" /><label for="least_frequently_asked_words"> Legritkábban kérdezett szavak szavak</label>
                            </div>
                            <div>                
                            	<input type="radio" name="test_variety" id="most_of_time_spoiled" value="most_of_time_spoiled" /><label for="most_of_time_spoiled"> Legtöbbször elrontott szavak</label>
                            </div>
                            <div>                
                            	<input type="radio" name="test_variety" id="oldest_asked" value="oldest_asked" /><label for="oldest_asked"> Legrégebben / még nem kérdezett szavak</label>
                            </div>
                            <div>                
                            	<input type="radio" name="test_variety" id="random" value="random" checked /><label for="random" onchange="topicSelectionFieldLock()"> Véletlenszerű</label>
                            </div>
                            <div>                
                            	<?php echo TopicGroupSelectionField::getTopicGroupSelectionField();	?>
                            </div>
                        </div>
                        
                        <div class="test-question-count-div">                
                            <label for="number_of_questions" >Kérdések száma:</label>
                            <input type="number" id="number_of_questions" name="number_of_questions" id="number_of_questions" value="10" min="<?php echo Environment::getMinTestQuestions(); ?>" max="<?php echo Environment::getMaxTestQuestions(); ?>" />
                        </div>
                    </div>
                    
                    <div class="test-get-button" >
                    	<input type="button" name="get_test_button" id="get_test_button" value="Kérem a tesztet" onclick="getTestForm()" />
                    </div>
        		</form>
    		</div>
    		<div id="include_test_table"></div>
    		<div id="include_bad_answers_table"></div>
    	</div>
    </body>
</html>