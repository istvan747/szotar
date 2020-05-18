<?php
use controller\classes\FormValidator;
use controller\classes\Logger;
use database\MySqlDatabasePDOConnection;
use modell\Meaning;
use modell\Word;
use database\MeaningMySqlPDO;
use database\WordMySqlPDO;

require_once '..' . DIRECTORY_SEPARATOR . 'autoloader.php';

$host  = $_SERVER['HTTP_HOST'];

if( isset( $_POST['word_add_submit_button'] ) ){
   $language = FormValidator::escapeString( $_POST['language'] );
   $wordA = FormValidator::escapeString( $_POST['wordA'] );
   $wordB = FormValidator::escapeString( $_POST['wordB'] );
   $topic = FormValidator::escapeString( $_POST['topic']);
   $word_class = FormValidator::escapeString( $_POST['word_class']);

   if( !FormValidator::wordValid( $wordA ) ){
       header("Location: http://$host/words_management?error=wordA_not_valid");
       exit;
   }
 
   if( !FormValidator::wordValid( $wordB ) ){
       header("Location: http://$host/words_management?error=wordB_not_valid");
       exit;
   }
   
   if( !FormValidator::topicValid( $topic ) ){
       header("Location: http://$host/words_management?error=topic_not_valid");
       exit;
   }
   
   if( !FormValidator::wordClassValid( $word_class ) ){
       header("Location: http://$host/words_management?error=word_class_not_valid");
       exit;
   }
   
   try{
       $sourceLanguage = '';
       $targetLanguage = '';
        if( $language === 'angol-magyar' ){
            $sourceLanguage = 'angol';
            $targetLanguage = 'magyar';
        }else if( $language === 'magyar-angol'){
            $sourceLanguage = 'magyar';
            $targetLanguage = 'angol';
        }else{
           header("Location: http://$host/words_management?error=language_not_valid");
           exit;
        }
        
        $meaning = new Meaning( new Word( $wordA ), new Word( $wordB ), $topic, $word_class );
        
        $conn = (new MySqlDatabasePDOConnection())->getConnection();
        $meaningDB = new MeaningMySqlPDO( $conn, new WordMySqlPDO($conn) );
        $meaningDB->saveMeaning($sourceLanguage, $targetLanguage, $meaning);
       
   }catch( PDOException $e ){
       Logger::log( $e->getCode() . ', ' . $e->getMessage() . ', ' . $e->getFile() . ', ' . $e->getLine() . ', ' . $e->getTraceAsString() );
       $conn = null;
       header("Location: http://$host/words_management");
       exit;
   }catch( Exception $e ){
       Logger::log( $e->getCode() . ', ' . $e->getMessage() . ', ' . $e->getFile() . ', ' . $e->getLine() . ', ' . $e->getTraceAsString() );
       $conn = null;
       header("Location: http://$host/words_management");
       exit;
   }catch( Error $e ){
       Logger::log( $e->getCode() . ', ' . $e->getMessage() . ', ' . $e->getFile() . ', ' . $e->getLine() . ', ' . $e->getTraceAsString() );
       $conn = null;
       header("Location: http://$host/words_management");
       exit;
   }finally{
       $conn = null;
   }
   
}

header("Location: http://$host/words_management?success=word_is_saved");
exit;

?>