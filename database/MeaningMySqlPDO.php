<?php
namespace database;

use environment\Environment;
use PDO;
use interfaces\MeaningDB;
use interfaces\MeaningJSONList;
use modell\Meaning;
use modell\Word;
use interfaces\WordDB;
use controller\classes\MeaningArrayList;

Environment::initEnvironment();

class MeaningMySqlPDO implements MeaningDB
{
   
    private $conn;
    private $wordDB;
    
    public function __construct( PDO $conn, WordDB $wordDB )
    {
        $this->conn = $conn;
        $this->wordDB = $wordDB;
    }
    
    public function saveMeaning( string $sourceLanguage, string $targetLanguage, Meaning $meaning): bool
    {
        if( $meaning !== null && $meaning->getWordA() !== null && $meaning->getWordB() !== null ){
            $this->conn->beginTransaction();
            $this->wordDB->saveWord( $sourceLanguage, $meaning->getWordA() );
            $this->wordDB->saveWord( $targetLanguage, $meaning->getWordB() );
            $sql = 'insert into jelentes ( angol_id, magyar_id, temakor, szofaj ) values( '
                    . '(select szo_id from angol where szo = :szoA), '
                    . '(select szo_id from magyar where szo = :szoB), '
                    . ':temakor,'
                    . ':szofaj '
                    . ')';
            $wordA = '';
            $wordB = '';
            if( strtolower( trim( $sourceLanguage) ) === 'magyar' ){
                $wordB = $meaning->getWordA()->getWord();
                $wordA = $meaning->getWordB()->getWord();
            }else if( strtolower( trim( $sourceLanguage) ) === 'angol' ){
                $wordA = $meaning->getWordA()->getWord();
                $wordB = $meaning->getWordB()->getWord();
            }else{
                $this->conn->rollBack();
            }
            $topic = $meaning->getTopic();
            $wordClass = $meaning->getWordClass();
            $stmt = $this->conn->prepare( $sql );
            $stmt->bindParam(':szoA', $wordA);
            $stmt->bindParam(':szoB', $wordB);
            $stmt->bindParam(':temakor', $topic );
            $stmt->bindParam(':szofaj', $wordClass );
            $stmt->execute();
            $this->conn->commit();
        }
        return false;
    }
    
    public function getMeaningByWordString(string $language, string $word):MeaningJSONList
    {
        $meaningList = new MeaningArrayList();
        $wordFieldName = '';
        if( strtolower( trim( $language ) ) == 'magyar' ){
            $wordFieldName = 'magyar_szo';  
        }else if( strtolower( trim( $language ) ) == 'angol' ){
            $wordFieldName = 'angol_szo';
        }else{
            return $meaningList;
        }        
        $sql = 'select jelentesek.magyar_szo, jelentesek.angol_szo, jelentesek.jelentes_id, jelentesek.angol_id, jelentesek.magyar_id, jelentesek.temakor, jelentesek.szofaj from( '
        . 'select magyar.szo as magyar_szo, jelentes.jelentes_id, jelentes.angol_id, jelentes.magyar_id, jelentes.temakor, jelentes.szofaj, angol.szo as angol_szo '
        . 'from magyar inner join ( jelentes inner join angol ) '
        . 'on magyar.szo_id = jelentes.magyar_id and angol.szo_id = jelentes.angol_id '
        . ') as jelentesek where jelentesek.' . $wordFieldName . ' like :word';
        $stmt = $this->conn->prepare( $sql );
        $stmt->bindParam(':word', $word );
        if( $stmt->execute() ){
            while( ($row = $stmt->fetch( PDO::FETCH_ASSOC )) !== false ){
                if( $wordFieldName == 'magyar_szo' ){
                    $wordA = new Word( $row['magyar_szo'], $row['magyar_id'] );
                    $wordB = new Word( $row['angol_szo'], $row['angol_id'] );
                }else{
                    $wordA = new Word( $row['angol_szo'], $row['angol_id'] );
                    $wordB = new Word( $row['magyar_szo'], $row['magyar_id'] );
                }
                $meaningList->addMeaning( new Meaning( $wordA, $wordB, $row['temakor'], $row['szofaj'], $row['jelentes_id'] ) );
            }
        }
        return $meaningList;        
    }

    public function getMeaningByWordObyect(Word $word): Meaning
    {
        $result = new Meaning();
        $sql = 'select jelentesek.magyar_szo, jelentesek.angol_szo, jelentesek.jelentes_id, jelentesek.angol_id, jelentesek.magyar_id, jelentesek.temakor, jelentesek.szofaj from( '
                . 'select magyar.szo as magyar_szo, jelentes.jelentes_id, jelentes.angol_id, jelentes.magyar_id, jelentes.temakor, jelentes.szofaj, angol.szo as angol_szo '
                . 'from magyar inner join ( jelentes inner join angol ) '
                . 'on magyar.szo_id = jelentes.magyar_id and angol.szo_id = jelentes.angol_id '
                . ') as jelentesek where jelentesek.magyar_szo = :magyar_szo and jelentesek.magyar_id = :magyar_id or jelentesek.angol_szo = :angol_szo and jelentesek.angol_id = :angol_id';
        $stmt = $this->conn->prepare( $sql );
        $word_word = $word->getWord();
        $word_id = $word->getId();
        $stmt->bindParam(':magyar_szo', $word_word );
        $stmt->bindParam(':magyar_id', $word_id );
        $stmt->bindParam(':angol_szo', $word_word );
        $stmt->bindParam(':angol_id', $word_id );
        if( $stmt->execute() ){
            while( ($row = $stmt->fetch( PDO::FETCH_ASSOC )) !== false ){
                $wordA = new Word( $row['magyar_szo'], $row['magyar_id'] );
                $wordB = new Word( $row['angol_szo'], $row['angol_id'] );
                $result = new Meaning( $wordA, $wordB, $row['temakor'], $row['szofaj'], $row['jelentes_id'] );
            }
        }
        return $result;
    }

    public function getMeaningById(int $id): Meaning
    {
        $result = new Meaning();
        $sql = 'select jelentesek.magyar_szo, jelentesek.angol_szo, jelentesek.jelentes_id, jelentesek.angol_id, jelentesek.magyar_id, jelentesek.temakor, jelentesek.szofaj from( '
                . 'select magyar.szo as magyar_szo, jelentes.jelentes_id, jelentes.angol_id, jelentes.magyar_id, jelentes.temakor, jelentes.szofaj, angol.szo as angol_szo '
                . 'from magyar inner join ( jelentes inner join angol ) '
                . 'on magyar.szo_id = jelentes.magyar_id and angol.szo_id = jelentes.angol_id '
                . ') as jelentesek where jelentesek.jelentes_id = :jelentes_id';
        $stmt = $this->conn->prepare( $sql );
        $stmt->bindParam(':jelentes_id', $id );
        if( $stmt->execute() ){
            while( ($row = $stmt->fetch( PDO::FETCH_ASSOC )) !== false ){
                $wordA = new Word( $row['magyar_szo'], $row['magyar_id'] );
                $wordB = new Word( $row['angol_szo'], $row['angol_id'] );
                $result = new Meaning( $wordA, $wordB, $row['temakor'], $row['szofaj'], $row['jelentes_id'] );
            }
        }
        return $result;
    }

    public function getMeaningByTopic(string $topic): MeaningJSONList
    {
        $meaningList = new MeaningArrayList();
        $sql = 'select jelentesek.magyar_szo, jelentesek.angol_szo, jelentesek.jelentes_id, jelentesek.angol_id, jelentesek.magyar_id, jelentesek.temakor, jelentesek.szofaj from( '
                . 'select magyar.szo as magyar_szo, jelentes.jelentes_id, jelentes.angol_id, jelentes.magyar_id, jelentes.temakor, jelentes.szofaj, angol.szo as angol_szo '
                . 'from magyar inner join ( jelentes inner join angol ) '
                . 'on magyar.szo_id = jelentes.magyar_id and angol.szo_id = jelentes.angol_id '
                . ') as jelentesek where jelentesek.temakor = :temakor';
        $stmt = $this->conn->prepare( $sql );
        $stmt->bindParam(':temakor', $topic );
        if( $stmt->execute() ){
            while( ($row = $stmt->fetch( PDO::FETCH_ASSOC )) !== false ){
                $wordA = new Word( $row['angol_szo'], $row['angol_id'] );
                $wordB = new Word( $row['magyar_szo'], $row['magyar_id'] );
                $meaningList->addMeaning( new Meaning( $wordA, $wordB, $row['temakor'], $row['szofaj'], $row['jelentes_id'] ) );
            }
        }
        return $meaningList;
    }

    public function getMeaningByWordClass(string $wordClass): MeaningJSONList
    {
        $meaningList = new MeaningArrayList();
        $sql = 'select jelentesek.magyar_szo, jelentesek.angol_szo, jelentesek.jelentes_id, jelentesek.angol_id, jelentesek.magyar_id, jelentesek.temakor, jelentesek.szofaj from( '
                . 'select magyar.szo as magyar_szo, jelentes.jelentes_id, jelentes.angol_id, jelentes.magyar_id, jelentes.temakor, jelentes.szofaj, angol.szo as angol_szo '
                . 'from magyar inner join ( jelentes inner join angol ) '
                . 'on magyar.szo_id = jelentes.magyar_id and angol.szo_id = jelentes.angol_id '
                . ') as jelentesek where jelentesek.szofaj = :szofaj';
        $stmt = $this->conn->prepare( $sql );
        $stmt->bindParam(':szofaj', $wordClass );
        if( $stmt->execute() ){
            while( ($row = $stmt->fetch( PDO::FETCH_ASSOC )) !== false ){
                $wordA = new Word( $row['angol_szo'], $row['angol_id'] );
                $wordB = new Word( $row['magyar_szo'], $row['magyar_id'] );
                $meaningList->addMeaning( new Meaning( $wordA, $wordB, $row['temakor'], $row['szofaj'], $row['jelentes_id'] ) );
            }
        }
        return $meaningList;
    }

    public function getMeaningContainString(string $str): MeaningJSONList
    {
        $meaningList = new MeaningArrayList();
        $sql = 'select jelentesek.magyar_szo, jelentesek.angol_szo, jelentesek.jelentes_id, jelentesek.angol_id, jelentesek.magyar_id, jelentesek.temakor, jelentesek.szofaj from( '
                . 'select magyar.szo as magyar_szo, jelentes.jelentes_id, jelentes.angol_id, jelentes.magyar_id, jelentes.temakor, jelentes.szofaj, angol.szo as angol_szo '
                . 'from magyar inner join ( jelentes inner join angol ) '
                . 'on magyar.szo_id = jelentes.magyar_id and angol.szo_id = jelentes.angol_id '
                . ') as jelentesek where jelentesek.magyar_szo like :string or jelentesek.angol_szo like :string';
        $stmt = $this->conn->prepare( $sql );
        $str = '%' . $str . '%';
        $stmt->bindParam(':string', $str );
        if( $stmt->execute() ){
            while( ($row = $stmt->fetch( PDO::FETCH_ASSOC )) !== false ){
                $wordA = new Word( $row['angol_szo'], $row['angol_id'] );
                $wordB = new Word( $row['magyar_szo'], $row['magyar_id'] );
                $meaningList->addMeaning( new Meaning( $wordA, $wordB, $row['temakor'], $row['szofaj'], $row['jelentes_id'] ) );
            }
        }
        return $meaningList;
    }
   
    public function filterMeaningByFields(string $wordContent, string $topic, string $word_class, int $limit): MeaningJSONList
    {
        $meaningList = new MeaningArrayList();
        $sql = 'select jelentesek.magyar_szo, jelentesek.angol_szo, jelentesek.jelentes_id, jelentesek.angol_id, jelentesek.magyar_id, jelentesek.temakor, jelentesek.szofaj from( '
                . 'select magyar.szo as magyar_szo, jelentes.jelentes_id, jelentes.angol_id, jelentes.magyar_id, jelentes.temakor, jelentes.szofaj, angol.szo as angol_szo '
                . 'from magyar inner join ( jelentes inner join angol ) '
                . 'on magyar.szo_id = jelentes.magyar_id and angol.szo_id = jelentes.angol_id '
                . ') as jelentesek where '
                . '(jelentesek.magyar_szo like :middlewordcontent '
                . 'or jelentesek.angol_szo like :middlewordcontent) ' 
                . 'and jelentesek.temakor like :topic '
                . 'and jelentesek.szofaj like :wordclass '
                . 'order by case '
                    . 'when (jelentesek.magyar_szo like :fullmatch or jelentesek.angol_szo like :fullmatch) then 1 '
                    . 'when (jelentesek.magyar_szo like :startwordcontent or jelentesek.angol_szo like :startwordcontent) then 2 '
                    . 'when (jelentesek.magyar_szo like :endwordcontent or jelentesek.angol_szo like :endwordcontent) then 4 '
                    . 'else 3 '
                . 'end '
                . 'limit ' . $this->escapeString( $limit );
        $stmt = $this->conn->prepare( $sql );
        $fullmatch = ( $wordContent === '' )?'%':$wordContent;
        $middlewordcontent = ( $wordContent === '' )?'%':('%' . $wordContent . '%');
        $startwordcontent = ( $wordContent === '' )?'%':( $wordContent . '%');
        $endwordcontent = ( $wordContent === '' )?'%':('%' . $wordContent );
        $topic = ( $topic === '' )?'%':$topic;
        $word_class = ( $word_class === '' )?'%':$word_class;
        $stmt->bindParam(':fullmatch', $fullmatch );
        $stmt->bindParam(':middlewordcontent', $middlewordcontent );
        $stmt->bindParam(':startwordcontent', $startwordcontent );
        $stmt->bindParam(':endwordcontent', $endwordcontent );
        $stmt->bindParam(':topic', $topic );
        $stmt->bindParam(':wordclass', $word_class );
        if( $stmt->execute() ){
            while( ($row = $stmt->fetch( PDO::FETCH_ASSOC )) !== false ){
                $wordA = new Word( $row['angol_szo'], $row['angol_id'] );
                $wordB = new Word( $row['magyar_szo'], $row['magyar_id'] );
                $meaningList->addMeaning( new Meaning( $wordA, $wordB, $row['temakor'], $row['szofaj'], $row['jelentes_id'] ) );
            }
        }
        return $meaningList;
    }

    public function updateMeaningById(int $id, Meaning $meaning): bool
    {
        if( $meaning !== null && $meaning->getWordA() !== null && $meaning->getWordB() !== null ){
            $amendMeaning = $this->getMeaningById( $id );
            if( $amendMeaning->getId() != -1 ){
                $issetMagyarWordA = $this->wordDB->issetWord('magyar', $amendMeaning->getWordA()->getWord());
                $issetMagyarWordB = $this->wordDB->issetWord('magyar', $amendMeaning->getWordB()->getWord());
                $issetAngolWordA = $this->wordDB->issetWord('angol', $amendMeaning->getWordA()->getWord());
                $issetAngolWordB = $this->wordDB->issetWord('angol', $amendMeaning->getWordB()->getWord());
                
                if( $issetMagyarWordA && $issetAngolWordB ){
                    $wordA_word = $meaning->getWordA()->getWord();
                    $wordA_id = $amendMeaning->getWordA()->getId();
                    $wordB_word = $meaning->getWordB()->getWord();
                    $wordB_id = $amendMeaning->getWordB()->getId();
                }else if( $issetMagyarWordB && $issetAngolWordA ){
                    $wordA_word = $meaning->getWordB()->getWord();
                    $wordA_id = $amendMeaning->getWordB()->getId();
                    $wordB_word = $meaning->getWordA()->getWord();
                    $wordB_id = $amendMeaning->getWordA()->getId();
                }else{
                    return false;
                }
                $topic = $meaning->getTopic();
                $word_class = $meaning->getWordClass();
                $meaning_id = $meaning->getId();
                $this->conn->beginTransaction();
                $sql = 'update jelentes set temakor = :temakor, szofaj = :szofaj where jelentes_id = :jelentes_id';
                $stmt = $this->conn->prepare( $sql );
                $stmt->bindParam(':temakor', $topic );
                $stmt->bindParam(':szofaj', $word_class );
                $stmt->bindParam(':jelentes_id', $meaning_id );
                $stmt->execute();
                
                $sql = 'update magyar set szo = :magyar_szo where szo_id = :szo_id';
                $stmt = $this->conn->prepare( $sql );
                $stmt->bindParam(':magyar_szo', $wordA_word );
                $stmt->bindParam(':szo_id', $wordA_id );
                $stmt->execute();
                
                $sql = 'update angol set szo = :angol_szo where szo_id = :szo_id';
                $stmt = $this->conn->prepare( $sql );
                $stmt->bindParam(':angol_szo', $wordB_word );
                $stmt->bindParam(':szo_id', $wordB_id );
                $stmt->execute();
                return $this->conn->commit();
            }
        }
        return false;
    }
    
    public function deleteMeaning(Meaning $meaning): bool
    {
        if( $meaning !== null ){
            return $this->deleteMeaningById( $meaning->getId() );
        }
        return false;
    }
    
    public function deleteMeaningById(int $id):bool
    {        
        $meaning = $this->getMeaningById( $id );
        if( $meaning !== null && $meaning->getWordA() !== null && $meaning->getWordB() !== null ){
            $sql = 'select magyar_id, angol_id from jelentes where jelentes_id = :jelentes_id';
            $stmt = $this->conn->prepare( $sql );
            $stmt->bindParam(':jelentes_id', $id );
            $magyar_id = -1;
            $angol_id = -1;
            if( $stmt->execute() ){
                if( ($row = $stmt->fetch( PDO::FETCH_ASSOC )) !== false ){
                    $magyar_id = $row['magyar_id'];
                    $angol_id = $row['angol_id'];
                }else{
                    return false;
                }
            }else{
                return false;
            }
            $meaning_id = $meaning->getId();
            $this->conn->beginTransaction();
            $sql = 'delete from jelentes where jelentes_id = :jelentes_id';
            $stmt = $this->conn->prepare( $sql );
            $stmt->bindParam(':jelentes_id', $meaning_id );
            $stmt->execute();
            $sql = 'delete from magyar where szo_id = :szo_id';
            $stmt = $this->conn->prepare( $sql );
            $stmt->bindParam(':szo_id', $magyar_id );
            $stmt->execute();
            $sql = 'delete from angol where szo_id = :szo_id';
            $stmt = $this->conn->prepare( $sql );
            $stmt->bindParam(':szo_id', $angol_id );
            $stmt->execute();
            return $this->conn->commit();
        }
        
    }
    
    private function getLanguageTableName( string $language ):string
    {
        $languageTables = Environment::getLanguageTableName();
        $language = strtolower( trim( $language ));
        switch( $language ){
            case 'magyar': return $languageTables['LANGUAGE_MAGYAR_TABLE'];
            case 'angol': return $languageTables['LANGUAGE_ANGOL_TABLE'];;
        }
        return '';
    }
    
    private function escapeString( string $str ){
        return htmlspecialchars( stripslashes( trim($str) ) );
    }
   
    public function __destruct()
    {
        $this->conn = null;
    }
    public function getTopicGroup(): array
    {
        $topicGroup = array();
        $sql = 'select temakor from jelentes group by temakor';
        if(  ($result = $this->conn->query( $sql )) !== false ){
            while( ($row = $result->fetch( PDO::FETCH_ASSOC )) !== false ){
                array_push( $topicGroup, $row['temakor'] );
            }
        }
        return $topicGroup;
    }


}

