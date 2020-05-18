<?php
use environment\Environment;
use controller\classes\FormValidator;
use controller\classes\Logger;
use database\MySqlDatabasePDOConnection;
use database\TestGeneratorMySqlPDO;

session_start();

require_once '..' . DIRECTORY_SEPARATOR . 'autoloader.php';

Environment::initEnvironment();

if( isset($_POST['languageDirection']) && isset( $_POST['testVariety']) && isset( $_POST['numberOfQuestion']) ){
    
    try{
    
        $languageDirection = FormValidator::escapeString($_POST['languageDirection']);
        $testVariety = FormValidator::escapeString($_POST['testVariety']);
        $numberOfQuestion = intval( $_POST['numberOfQuestion'] );
        
        $sourceLanguage = '';
        $targetLanguage = '';
        
        if( $languageDirection === 'magyar-angol' ){
            $sourceLanguage = 'magyar';
            $targetLanguage = 'angol';
        }else{
            $sourceLanguage = 'angol';
            $targetLanguage = 'magyar';
        }
        
        $conn = (new MySqlDatabasePDOConnection())->getConnection();
        $testGenerator = new TestGeneratorMySqlPDO( $conn );

        if( $testVariety === 'least_frequently_asked_words' ){
            $test = $testGenerator->getLeastFrequentlyAskedTest( $sourceLanguage, $targetLanguage, $numberOfQuestion );
            echo $test->getTestQuestionsInJSON();
        }
        
        if( $testVariety === 'most_of_time_spoiled' ){
            $test = $testGenerator->getMostOfTimeSpoiledTest( $sourceLanguage, $targetLanguage, $numberOfQuestion );
            echo $test->getTestQuestionsInJSON(); 
        }
        
        if( $testVariety === 'oldest_asked' ){
            $test = $testGenerator->getOldestAskedTest( $sourceLanguage, $targetLanguage, $numberOfQuestion );
            echo $test->getTestQuestionsInJSON(); 
        }
        
        if( $testVariety === 'random' && isset( $_POST['topics'] )){
            $topics = $_POST['topics'];
            for( $i = 0; $i < count( $topics ); $i++ ){
                $topics[$i] = str_replace('_', ' ', $topics[$i] );
            }
            $test = $testGenerator->getRandomTestByTopic( $topics, $sourceLanguage, $targetLanguage, $numberOfQuestion );
            echo $test->getTestQuestionsInJSON();           
        }
        
    }catch( PDOException $e ){
        Logger::log( $e->getCode() . ', ' . $e->getMessage() . ', ' . $e->getFile() . ', ' . $e->getLine() . ', ' . $e->getTraceAsString() );
    }catch( Exception $e ){
        Logger::log( $e->getCode() . ', ' . $e->getMessage() . ', ' . $e->getFile() . ', ' . $e->getLine() . ', ' . $e->getTraceAsString() );
    }catch( Error $e ){
        Logger::log( $e->getCode() . ', ' . $e->getMessage() . ', ' . $e->getFile() . ', ' . $e->getLine() . ', ' . $e->getTraceAsString() );
    }finally{
        $conn = null;
    }
}
exit;
?>