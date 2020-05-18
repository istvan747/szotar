<?php
namespace modell;

class User
{
    
    private $userName;
    private $password;
    private $email;
    private $loggedIn;
    private $admin;

    public function __construct( string $userName = '', string $password = '', string $email = '', bool $loggedIn = false, bool $admin = false ){
       $this->userName = $userName;
       $this->password = $password;
       $this->email = $email;
       $this->loggedIn = $loggedIn;
       $this->admin = $admin;
    }
    
    public function isAdmin():bool
    {
        return $this->admin;
    }
    
    public function setAdmin( bool $admin ):void
    {
        $this->admin = $admin;
    }
    
    public function logIn():void
    {
        $this->loggedIn = true;
    }
    
    public function logOut():void
    {
        $this->loggedIn = false;
    }
    
    public function getLoggedIn():bool
    {
        return $this->loggedIn;    
    }
    
    public function getUserName():string
    {
        return $this->userName;
    }

    public function getPassword():string
    {
        return $this->password;
    }
    
    public function getEmail():string
    {
        return $this->email;
    }
    
    public function setUserName(string $userName):void
    {
        $this->userName = $userName;
    }
    
    public function setPassword( string $password):void
    {
        $this->password = $password;
    }
    
    public function setEmail( string $email):void
    {
        $this->email = $email;
    }
    
    public function equals( User $user ):bool
    {
        if( $this == $user ){
            return true;
        }
        if( $user == null ){
            return false;
        }
        if( $user instanceof User ){
            if( $this->userName === $user->userName ){
                return true;
            }
        }
        return false;
    }
    
    public function equalsStrict( User $user ):bool
    {
        if( $this == $user ){
            return true;
        }
        if( $user == null ){
            return false;
        }
        if( $user instanceof User ){
            if( $this->userName !== $user->userName ){
                return false;
            }else if( $this->email !== $user->email ){
                return false;
            }else if( $this->loggedIn !== $user->loggedIn ){
                return false;
            }else{
                return true;
            }
        }
        return false;
    }
}

