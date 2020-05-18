<?php
namespace controller\classes;

class Session
{
    
    public static function setLogInSession():void
    {
        $_SESSION['loggedIn'] = true;
    }
    
    public static function setAdminSession():void
    {
        $_SESSION['admin'] = true;
    }
    
    public static function setUserNameSession( string $userName ):void
    {
        $_SESSION['userName'] = $userName;
    }
    
    public static function getUserNameSession():string
    {
        if( isset( $_SESSION['userName'] ) )
            return $_SESSION['userName'];
        return '';
    }
    
    public static function logOutSession():void
    {
        session_destroy();
    }
    
    public static function isLoggedIn():bool
    {
        return isset( $_SESSION['loggedIn']) && $_SESSION['loggedIn'];
    }
    
    public static function isAdmin():bool
    {
        return isset( $_SESSION['admin']) && $_SESSION['admin'];
    }
    
}

