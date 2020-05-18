<?php
namespace database;

use PDO;
use interfaces\TestDB;
use modell\TestValidator;

class TestMySqliPDO implements TestDB
{
    
    private $conn;
    
    public function __construct( PDO $conn ){
        $this->conn = $conn;
    }
    
    public function saveTest(TestValidator $testValidator): bool
    {
        $this->conn->beginTransaction();
        $sql = 'insert into teszt ( felhasznalonev, forrasnyelv, celnyelv ) values ( :felhasznalonev, :forrasnyelv, :celnyelv )';
        $stmt = $this->conn->prepare( $sql );
        $userName = $testValidator->getUserName();        
        $sourceLanguage = $testValidator->getSourceLanguage();
        $targetLanguage = $testValidator->getTargetLanguage();        
        $stmt->bindParam( ':felhasznalonev', $userName );        
        $stmt->bindParam( ':forrasnyelv', $sourceLanguage );
        $stmt->bindParam( ':celnyelv', $targetLanguage );
        $stmt->execute();
        $testID = $this->conn->lastInsertId();
        $jelentesID = '';
        foreach( $testValidator->getBadAnswers() as $badAnswers ){
            $sql = 'insert into teszt_szavai ( jelentes_id, teszt_id, sikeres_valasz ) values ( :jelentes_id, :teszt_id, :sikeres_valasz)';
            $stmt = $this->conn->prepare( $sql );
            $jelentesID = $badAnswers->getID();
            $sikeres_valasz = false;
            $stmt->bindParam( ':jelentes_id', $jelentesID );
            $stmt->bindParam( ':teszt_id', $testID );
            $stmt->bindParam( ':sikeres_valasz', $sikeres_valasz );
            $stmt->execute();            
        }
        foreach( $testValidator->getGoodAnswers() as $goodAnswers ){
            $sql = 'insert into teszt_szavai ( jelentes_id, teszt_id, sikeres_valasz ) values ( :jelentes_id, :teszt_id, :sikeres_valasz)';
            $stmt = $this->conn->prepare( $sql );
            $jelentesID = $goodAnswers->getID();
            $sikeres_valasz = true;
            $stmt->bindParam( ':jelentes_id', $jelentesID );
            $stmt->bindParam( ':teszt_id', $testID );
            $stmt->bindParam( ':sikeres_valasz', $sikeres_valasz );
            $stmt->execute();
        }
        return $this->conn->commit();
    }

}

