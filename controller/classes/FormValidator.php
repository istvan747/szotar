<?php
namespace controller\classes;

use environment\Environment;

Environment::initEnvironment();

class FormValidator
{
    
    public static function usernameValid( string $userName ):bool
    {
        $maxLength = Environment::getUserNameMaxLength();
        $minLength = Environment::getUserNameMinLength();
        $regEx = Environment::getUserNameRegex();
        $userNameLength = strlen( $userName );
        if( $userNameLength >= $minLength && $userNameLength <= $maxLength && preg_match( $regEx, $userName) )
            return true;
        return false;
    }
    
    public static function passwordValid( string $password ):bool
    {
        $maxLength = Environment::getPasswordMaxLength();
        $minLength = Environment::getPasswordMinLength();
        $regEx = Environment::getPasswordRegex();
        $passwordLength = strlen( $password );
        if( $passwordLength >= $minLength && $passwordLength <= $maxLength && preg_match( $regEx, $password) )
            return true;
        return false;
    }
    
    public static function emailValid( string $email ):bool
    {
        $maxLength = Environment::getEmailMaxLength();
        $minLength = Environment::getEmailMinLength();
        $regEx = Environment::getEmailRegex();
        $emailLength = strlen( $email );
        if( $emailLength >= $minLength && $emailLength <= $maxLength && preg_match( $regEx, $email) )
            return true;
        return false;
    }
    
    public static function wordValid( string $word ):bool
    {
        $maxLength = Environment::getWordMaxLength();
        $minLength = Environment::getWordMinLength();
        $regEx = Environment::getWordRegex();
        $wordLength = strlen( $word );
        if( $wordLength >= $minLength && $wordLength <= $maxLength && preg_match( $regEx, $word) )
            return true;
        return false;
    }
    
    public static function topicValid( string $topic ):bool
    {
        $maxLength = Environment::getTopicMaxLength();
        $minLength = Environment::getTopicMinLength();
        $regEx = Environment::getTopicRegex();
        $wordLength = strlen( $topic );
        if( $wordLength >= $minLength && $wordLength <= $maxLength && preg_match( $regEx, $topic) )
            return true;
        return false;
    }
    
    public static function wordClassValid( string $wordClass ):bool
    {
        $maxLength = Environment::getWordClassMaxLength();
        $minLength = Environment::getWordClassMinLength();
        $regEx = Environment::getWordClassRegex();
        $wordLength = strlen( $wordClass );
        if( $wordLength >= $minLength && $wordLength <= $maxLength && preg_match( $regEx, $wordClass) )
            return true;
        return false;
    }
    
    public static function escapeString( string $str ){
        return htmlspecialchars( stripslashes( trim( $str ) ) );
    }
    
    
    
}

?>

