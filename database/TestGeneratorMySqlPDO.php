<?php

namespace database;

use interfaces\TestGenerator;
use modell\Test;
use PDO;
use controller\classes\MeaningArrayList;
use modell\Meaning;
use modell\Word;
use controller\classes\Session;

class TestGeneratorMySqlPDO implements TestGenerator
{
    
    private $conn;
    
    public function __construct( PDO $conn )
    {
        $this->conn = $conn;
    }
    
    public function getLeastFrequentlyAskedTest(string $sourceLanguege, string $targetLanguage, int $questionCount): Test
    {
        $test = new Test();
        
        if( !is_int( $questionCount ) || $questionCount < 0 ){
            $questionCount = 0;
        }
        
        $sql = 'select osszes_jelentes.jelentes_id, osszes_jelentes.magyar_id, osszes_jelentes.magyar_szo, osszes_jelentes.angol_id, osszes_jelentes.angol_szo, osszes_jelentes.temakor, osszes_jelentes.szofaj from '
            . '(select jelentes.jelentes_id, jelentes.magyar_id, magyar.szo as magyar_szo, jelentes.angol_id, angol.szo as angol_szo, jelentes.temakor, jelentes.szofaj from '
            . '(magyar inner join jelentes) inner join angol on magyar.szo_id = jelentes.magyar_id and angol.szo_id = jelentes.angol_id) as osszes_jelentes '
            . 'left join '
            . '(select teszt_szavai.jelentes_id, count( teszt_szavai.jelentes_id ) as kerdesek_szama from teszt inner join teszt_szavai on teszt.teszt_id = teszt_szavai.teszt_id and felhasznalonev = :felhasznalonev group by teszt_szavai.jelentes_id) as kerdezett_szavak '
            . 'on osszes_jelentes.jelentes_id = kerdezett_szavak.jelentes_id '
            . 'order by kerdezett_szavak.kerdesek_szama asc limit ' . $this->escapeString( $questionCount );
        $stmt = $this->conn->prepare( $sql );
        $userName = Session::getUserNameSession();
        $stmt->bindParam( ':felhasznalonev', $userName );
        if( ( $stmt->execute()) !== false ){
            $meaningList = new MeaningArrayList();
            while( ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) !== false ){
                if( $sourceLanguege === 'magyar' ){
                    $wordA = new Word( $row['magyar_szo'], $row['magyar_id']);
                    $wordB = new Word( $row['angol_szo'], $row['angol_id']);
                }else{
                    $wordA = new Word( $row['angol_szo'], $row['angol_id']);
                    $wordB = new Word( $row['magyar_szo'], $row['magyar_id']);
                }
                $meaning = new Meaning( $wordA, $wordB, $row['temakor'], $row['szofaj'], $row['jelentes_id'] );
                $meaningList->addMeaning($meaning);
            }
            $test = new Test( $sourceLanguege, $targetLanguage, $meaningList, Session::getUserNameSession() );
        }
        return $test;
    }
    
    public function getMostOfTimeSpoiledTest( string $sourceLanguege, string $targetLanguage, int $questionCount ): Test
    {
        $test = new Test();
        
        if( !is_int( $questionCount ) || $questionCount < 0 ){
            $questionCount = 0;
        }
        
        $sql = 'select osszes_jelentes.jelentes_id, osszes_jelentes.magyar_id, osszes_jelentes.magyar_szo, osszes_jelentes.angol_id, osszes_jelentes.angol_szo, osszes_jelentes.temakor, osszes_jelentes.szofaj from '
            . '(select jelentes.jelentes_id, jelentes.magyar_id, magyar.szo as magyar_szo, jelentes.angol_id, angol.szo as angol_szo, jelentes.temakor, jelentes.szofaj from '
            . '(magyar inner join jelentes) inner join angol on magyar.szo_id = jelentes.magyar_id and angol.szo_id = jelentes.angol_id) as osszes_jelentes '
            . 'left join '
            . '(select teszt_szavai.jelentes_id, count( teszt_szavai.jelentes_id ) as hibas_valaszok_szama from teszt inner join teszt_szavai on teszt.teszt_id = teszt_szavai.teszt_id and felhasznalonev = :felhasznalonev where sikeres_valasz = false group by teszt_szavai.jelentes_id) as hibas_valaszok '
            . 'on osszes_jelentes.jelentes_id = hibas_valaszok.jelentes_id '
            . 'order by hibas_valaszok.hibas_valaszok_szama desc limit ' . $this->escapeString( $questionCount );
        $stmt = $this->conn->prepare( $sql );
        $userName = Session::getUserNameSession();
        $stmt->bindParam( ':felhasznalonev', $userName );
        if( ( $stmt->execute()) !== false ){
            $meaningList = new MeaningArrayList();
            while( ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) !== false ){
                if( $sourceLanguege === 'magyar' ){
                    $wordA = new Word( $row['magyar_szo'], $row['magyar_id']);
                    $wordB = new Word( $row['angol_szo'], $row['angol_id']);
                }else{
                    $wordA = new Word( $row['angol_szo'], $row['angol_id']);
                    $wordB = new Word( $row['magyar_szo'], $row['magyar_id']);
                }
                $meaning = new Meaning( $wordA, $wordB, $row['temakor'], $row['szofaj'], $row['jelentes_id'] );
                $meaningList->addMeaning($meaning);
            }
            $test = new Test( $sourceLanguege, $targetLanguage, $meaningList, Session::getUserNameSession() );
        }
        return $test;
    }

    public function getOldestAskedTest( string $sourceLanguege, string $targetLanguage, int $questionCount ): Test
    {
        $test = new Test();
        
        if( !is_int( $questionCount ) || $questionCount < 0 ){
            $questionCount = 0;
        }
        
        $sql = 'select osszes_jelentes.jelentes_id, osszes_jelentes.magyar_id, osszes_jelentes.magyar_szo, osszes_jelentes.angol_id, osszes_jelentes.angol_szo, osszes_jelentes.temakor, osszes_jelentes.szofaj from '
            . '( select jelentes.jelentes_id, jelentes.magyar_id, magyar.szo as magyar_szo, jelentes.angol_id, angol.szo as angol_szo, jelentes.temakor, jelentes.szofaj from '
            . '(magyar inner join jelentes) inner join angol on magyar.szo_id = jelentes.magyar_id and angol.szo_id = jelentes.angol_id ) as osszes_jelentes '
            . 'left join '
            . '(select teszt_szavai.jelentes_id, min( teszt.kitoltes_datuma ) as kitoltes_datuma from (teszt_szavai inner join teszt on teszt_szavai.teszt_id = teszt.teszt_id and teszt.felhasznalonev = :felhasznalonev ) group by teszt_szavai.jelentes_id order by teszt.kitoltes_datuma desc) as kerdezett_jelentes '
            . 'on osszes_jelentes.jelentes_id = kerdezett_jelentes.jelentes_id order by '
            . 'case when kerdezett_jelentes.kitoltes_datuma like null then kerdezett_jelentes.kitoltes_datuma end asc, '
            . 'case when kerdezett_jelentes.kitoltes_datuma like "%" then kerdezett_jelentes.kitoltes_datuma end asc limit ' . $this->escapeString( $questionCount );
        $stmt = $this->conn->prepare( $sql );
        $userName = Session::getUserNameSession();
        $stmt->bindParam( ':felhasznalonev', $userName );
        if( ( $stmt->execute()) !== false ){
            $meaningList = new MeaningArrayList();
            while( ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) !== false ){
                if( $sourceLanguege === 'magyar' ){
                    $wordA = new Word( $row['magyar_szo'], $row['magyar_id']);
                    $wordB = new Word( $row['angol_szo'], $row['angol_id']);
                }else{
                    $wordA = new Word( $row['angol_szo'], $row['angol_id']);
                    $wordB = new Word( $row['magyar_szo'], $row['magyar_id']);
                }
                $meaning = new Meaning( $wordA, $wordB, $row['temakor'], $row['szofaj'], $row['jelentes_id'] );
                $meaningList->addMeaning($meaning);
            }
            $test = new Test( $sourceLanguege, $targetLanguage, $meaningList, Session::getUserNameSession() );
        }
        return $test;
    }

    public function getRandomTestByTopic( array $topicList, string $sourceLanguege, string $targetLanguage, int $questionCount): Test
    {
        $test = new Test();
        
        if( !is_int( $questionCount ) || $questionCount < 0 ){
            $questionCount = 0;
        }
        
        $topicCount = count( $topicList );
        $topicCondition = ' like "%" ';
        if( $topicCount > 0 && !in_array( 'alltopic', $topicList ) ){
            $topicCondition = ' in ( ';
            for( $i = 0; $i < $topicCount; $i++ ){
                $topicCondition .= '"' . $this->escapeString( $topicList[$i]) . '"' . (( $i < $topicCount - 1 )?',':'');
            }
            $topicCondition .= ') ';
        }
        
        $sql = 'select magyar.szo as magyar, magyar.szo_id as magyar_id, angol.szo_id as angol_id, angol.szo as angol, jelentes.temakor, jelentes.szofaj, jelentes.jelentes_id ' 
            . 'from magyar inner join ( jelentes inner join angol ) on magyar.szo_id = jelentes.magyar_id and angol.szo_id = jelentes.angol_id '
            . 'where jelentes.temakor ' . $topicCondition .  ' order by rand() limit ' . $this->escapeString( $questionCount );
        
        if( ( $result = $this->conn->query( $sql )) !== false ){            
            $meaningList = new MeaningArrayList();
            while( ( $row = $result->fetch( PDO::FETCH_ASSOC ) ) !== false ){
                if( $sourceLanguege === 'magyar' ){
                    $wordA = new Word( $row['magyar'], $row['magyar_id']);
                    $wordB = new Word( $row['angol'], $row['angol_id']);
                }else{
                    $wordA = new Word( $row['angol'], $row['angol_id']);
                    $wordB = new Word( $row['magyar'], $row['magyar_id']);
                }
                $meaning = new Meaning( $wordA, $wordB, $row['temakor'], $row['szofaj'], $row['jelentes_id'] );
                $meaningList->addMeaning($meaning);
            }
            $test = new Test( $sourceLanguege, $targetLanguage, $meaningList, Session::getUserNameSession() );
        }
        return $test;
        
    }
    
    private function escapeString( string $str ){
        return  htmlspecialchars( stripslashes( trim( $str ) ) );
    }


}

