<?php 
session_start();

use controller\classes\FormValidator;
use controller\classes\Logger;
use database\MySqlDatabasePDOConnection;
use database\UserMySqlPDO;
use controller\classes\Session;

require_once '..' . DIRECTORY_SEPARATOR . 'autoloader.php';

$host  = $_SERVER['HTTP_HOST'];

if( isset( $_POST['login_submit'] ) ){
   $userName = FormValidator::escapeString( $_POST['username'] );
   $password = FormValidator::escapeString( $_POST['password'] );
   
   if( !FormValidator::usernameValid($userName) ){
       header("Location: http://$host/?error=username_not_valid");
       exit;
   }

   if( !FormValidator::passwordValid($password) ){
       header("Location: http://$host/?error=password_not_valid");
       exit;
   }
   
   try{
       $conn = (new MySqlDatabasePDOConnection())->getConnection();
       $userDB = new UserMySqlPDO( $conn );
       $user = $userDB->getUserByName( $userName );
       if( $user->getUserName() !== '' ){
           
           if( password_verify( $password , $user->getPassword() )){
               Session::setLogInSession();
               Session::setUserNameSession( $userName );
               if( $user->isAdmin() ){
                   Session::setAdminSession();
               }
           }          
       }
   }catch( PDOException $e ){
       Logger::log( $e->getCode() . ', ' . $e->getMessage() . ', ' . $e->getFile() . ', ' . $e->getLine() . ', ' . $e->getTraceAsString() );
       $conn = null;
       header("Location: http://$host/");
       exit;
   }catch( Exception $e ){
       Logger::log( $e->getCode() . ', ' . $e->getMessage() . ', ' . $e->getFile() . ', ' . $e->getLine() . ', ' . $e->getTraceAsString() );
       $conn = null;
       header("Location: http://$host/");
       exit;
   }catch( Error $e ){
       Logger::log( $e->getCode() . ', ' . $e->getMessage() . ', ' . $e->getFile() . ', ' . $e->getLine() . ', ' . $e->getTraceAsString() );
       $conn = null;
       header("Location: http://$host");
       exit;
   }finally{
       $conn = null;
   }
}

header("Location: http://$host/");
exit;


?>