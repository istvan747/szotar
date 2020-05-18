<?php
namespace interfaces;

use modell\User;

interface UserDB
{    
    public function saveUser( User $user ):bool;
    
    public function getUserByName( string $name ):User;
    
    public function getUserByEmail( string $email ):User;
    
    public function updateUserEmail( string $userName, string $email ):bool;
    
    public function updateUserPassword( string $userName, string $password ):bool;
    
    public function deleteUserByName( string $name ):bool;
    
    public function deleteUser( User $user ):bool;
    
}

