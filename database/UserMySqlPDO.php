<?php
namespace database;

use PDO;
use interfaces\UserDB;
use modell\User;

class UserMySqlPDO implements UserDB
{
    
    private $conn;
 
    public function __construct( PDO $conn )
    {
        $this->conn = $conn;
    }
    
    public function saveUser(User $user): bool
    {
        if( $user !== null ){
            $sql = 'insert into felhasznalo ( felhasznalonev, jelszo, email ) values (:username, :password, :email)';
            $username = $user->getUserName();
            $password = $user->getPassword();
            $email = $user->getEmail();
            $stmt = $this->conn->prepare( $sql );
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':email', $email);
            return $stmt->execute();
        }
        return false;
    }
    
    public function getUserByEmail(string $email): User
    {
        $user = new User();
        $sql = 'select felhasznalonev, jelszo, email, admin from felhasznalo where email = :email';
        $stmt = $this->conn->prepare( $sql );
        $stmt->bindParam(':email', $email );
        if( $stmt->execute() ){
            if( ( $row = $stmt->fetch( PDO::FETCH_ASSOC )) !== false ){
                $user = new User( $row['felhasznalonev'], $row['jelszo'], $row['email'], false, $row['admin'] );
            }
        }
        return $user;
    }

    public function getUserByName(string $name): User
    {
        $user = new User();
        $sql = 'select felhasznalonev, jelszo, email, admin from felhasznalo where felhasznalonev = :username';
        $stmt = $this->conn->prepare( $sql );
        $stmt->bindParam(':username', $name );
        if( $stmt->execute() ){
            if( ( $row = $stmt->fetch( PDO::FETCH_ASSOC )) !== false ){
                $user = new User( $row['felhasznalonev'], $row['jelszo'], $row['email'], false, $row['admin'] );
            }
        }
        return $user;
    }

    public function updateUserEmail(string $userName, string $email ): bool
    {
        $sql = 'update felhasznalo set email = :email where felhasznalonev = :username';
        $stmt = $this->conn->prepare( $sql );
        $stmt->bindParam(':email', $email );
        $stmt->bindParam(':username', $userName );
        return $stmt->execute();
    }
    
    public function updateUserPassword(string $userName, string $password ): bool
    {
        $sql = 'update felhasznalo set jelszo = :password where felhasznalonev = :username';
        $stmt = $this->conn->prepare( $sql );
        $stmt->bindParam(':password', $password );
        $stmt->bindParam(':username', $userName );
        return $stmt->execute();
    }

    public function deleteUserByName(string $name): bool
    {
        $sql = 'delete from felhasznalo where felhasznalonev = :username';
        $stmt = $this->conn->prepare( $sql );
        $stmt->bindParam(':username', $name );
        return $stmt->execute();
    }
    
    public function deleteUser(User $user): bool
    {
        if( $user !== null ){
            $sql = 'delete from felhasznalo where felhasznalonev = :username';
            $stmt = $this->conn->prepare( $sql );
            $userName = $user->getUserName();
            $stmt->bindParam(':username', $userName );
            return $stmt->execute();
        }
        return false;
    }

}

