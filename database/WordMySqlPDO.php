<?php
namespace database;

use PDO;
use interfaces\WordDB;
use modell\Word;
use environment\Environment;

Environment::initEnvironment();

class WordMySqlPDO implements WordDB
{
    private $conn;
    
    public function __construct( PDO $conn ){
        $this->conn = $conn;
    }
    
    public function saveWord(string $language, Word $word): bool
    {
        if( $word !== null ){
            $tableName = Environment::getLanguageTableName($language);
            $sql = 'insert into ' . $this->escapeString( $tableName ) . '( szo ) values ( :szo )';
            $stmt = $this->conn->prepare( $sql );
            $wordString = $word->getWord();
            $stmt->bindParam(':szo', $wordString );
            return $stmt->execute();
        }
        return false;
    }
    
    public function issetWord(string $language, string $word): bool
    {
        $tableName = Environment::getLanguageTableName( $language );
        $sql = 'select count( szo ) as count_word from ' . $this->escapeString( $tableName ) . ' where szo = :szo';
        $stmt = $this->conn->prepare( $sql );
        $stmt->bindParam(':szo', $word );
        if( $stmt->execute() ){
            return intval(($stmt->fetch( PDO::FETCH_ASSOC))['count_word']) > 0;
        }
        return false;
    }

    public function getWordByWordString(string $language, string $word): Word
    {
        $ResultWord = new Word();
        $tableName = Environment::getLanguageTableName( $language );
        $sql = 'select szo_id, szo from ' . $this->escapeString( $tableName ) . ' where szo = :szo';
        $stmt = $this->conn->prepare( $sql );
        $stmt->bindParam(':szo', $word );
        if( $stmt->execute() ){
            while( ($row = $stmt->fetch( PDO::FETCH_ASSOC)) !== false ){
               $ResultWord = new Word( $row['szo'], $row['szo_id'] ); 
            }
        }
        return $ResultWord;
    }

    public function getWordById(string $language, int $id): Word
    {
        $ResultWord = new Word();
        $tableName = Environment::getLanguageTableName( $language );
        $sql = 'select szo_id, szo from ' . $this->escapeString( $tableName ) . ' where szo_id = :id';
        $stmt = $this->conn->prepare( $sql );
        $stmt->bindParam(':id', $id );
        if( $stmt->execute() ){
            while( ($row = $stmt->fetch( PDO::FETCH_ASSOC)) !== false ){
                $ResultWord = new Word( $row['szo'], $row['szo_id'] );
            }
        }
        return $ResultWord;
    }
    
    public function getWord(string $language, Word $word): Word
    {
        $ResultWord = new Word();
        if( $word !== null ){
            $tableName = Environment::getLanguageTableName( $language );
            $sql = 'select szo_id, szo from ' . $this->escapeString( $tableName ) . ' where szo_id = :id and szo = :szo';
            $stmt = $this->conn->prepare( $sql );
            $wordString = $word->getWord();
            $wordId = $word->getId();
            $stmt->bindParam(':id', $wordId );
            $stmt->bindParam(':szo', $wordString );
            if( $stmt->execute() ){
                while( ($row = $stmt->fetch( PDO::FETCH_ASSOC)) !== false ){
                    $ResultWord = new Word( $row['szo'], $row['szo_id'] );
                }
            }
        }
        return $ResultWord;
    }

    public function getWordContainsString(string $language, string $str): array
    {
        $wordArray = array();
        $tableName = Environment::getLanguageTableName( $language );
        $sql = 'select szo_id, szo from ' . $this->escapeString( $tableName ) . ' where szo like :string ';
        $stmt = $this->conn->prepare( $sql );
        $str = '%' . $str . '%';
        $stmt->bindParam(':string', $str );
        if( $stmt->execute() ){
            while( ($row = $stmt->fetch( PDO::FETCH_ASSOC )) !== false ){
                array_push( $wordArray, new Word( $row['szo'], $row['szo_id'] ));
            }
        }
        return $wordArray;
    }

    public function updateWord(string $language, Word $oldWord, Word $newWord): bool
    {
        if( $oldWord !== null && $newWord !== null ){
            $tableName = Environment::getLanguageTableName( $language );
            $sql = 'update ' . $this->escapeString( $tableName ) . ' set szo = :new_word where szo_id = :old_id and szo = :old_word';
            $stmt = $this->conn->prepare( $sql );
            $new_word = $newWord->getWord();
            $old_word = $oldWord->getWord();
            $old_id = $oldWord->getId();
            $stmt->bindParam(':new_word', $new_word);
            $stmt->bindParam(':old_id', $old_id);
            $stmt->bindParam(':old_word', $old_word);
            echo $stmt->queryString;
            return $stmt->execute();
        }
        return false;
    }
    
    public function deleteWordByWordString(string $language, string $word): bool
    {
        $tableName = Environment::getLanguageTableName( $language );
        $sql = 'delete from ' . $this->escapeString( $tableName ) . ' where szo = :szo';
        $stmt = $this->conn->prepare( $sql );
        $stmt->bindParam( ':szo', $word );
        return $stmt->execute();
    }
    
    public function deleteWordById( string $language, int $id ): bool
    {
        $tableName = Environment::getLanguageTableName( $language );
        $sql = 'delete from ' . $this->escapeString( $tableName ) . ' where szo_id = :szo_id';
        $stmt = $this->conn->prepare( $sql );
        $stmt->bindParam( ':szo_id', $id );
        return $stmt->execute();
    }
    
    public function deleteWord(string $language, Word $word): bool
    {
        $tableName = Environment::getLanguageTableName( $language );
        $sql = 'delete from ' . $this->escapeString( $tableName ) . ' where szo_id = :szo_id and szo = :szo';
        $stmt = $this->conn->prepare( $sql );
        $szo_id = $word->getId();
        $szo = $word->getWord();
        $stmt->bindParam( ':szo_id', $szo_id );
        $stmt->bindParam( ':szo', $szo );
        return $stmt->execute();
    }
    
    private function escapeString( string $str ){
        return htmlspecialchars( stripslashes( trim($str) ) );
    }
    
    public function __destruct(){
        $this->conn = null;
    }

}

