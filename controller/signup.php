<?php
require_once '..' . DIRECTORY_SEPARATOR . 'autoloader.php';
use controller\classes\FormValidator;
use modell\User;
use database\UserMySqlPDO;
use database\MySqlDatabasePDOConnection;
use controller\classes\Logger;

$host  = $_SERVER['HTTP_HOST'];

if( isset( $_POST['registration_submit']) ){
    
    $userName = FormValidator::escapeString( $_POST['username'] );
    $email = FormValidator::escapeString( $_POST['email'] );
    $password = FormValidator::escapeString( $_POST['password'] );
    $password_confirm = FormValidator::escapeString( $_POST['password_confirm'] );
    
    if( !FormValidator::usernameValid($userName) ){
        header("Location: http://$host/?error=username_not_valid");
        exit;
    }
    
    if( !FormValidator::emailValid($email) ){
        header("Location: http://$host/?error=email_not_valid");
        exit;
    }
    
    if( !FormValidator::passwordValid($password) ){
        header("Location: http://$host/?error=password_not_valid");
        exit;
    }
    
    if( $password !== $password_confirm ){
        header("Location: http://$host/?error=confirm_password_not_valid");
        exit;
    }
    
    try{
        $conn = (new MySqlDatabasePDOConnection())->getConnection();
        $userDB = new UserMySqlPDO( $conn );
        $user = new User( $userName, password_hash( $password, PASSWORD_DEFAULT ), $email, false, false );
        $userDB->saveUser( $user );
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

header("Location: http://$host/?success=registration");
exit;

?>