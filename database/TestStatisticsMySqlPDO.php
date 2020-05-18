<?php
namespace database;

use interfaces\TestStatisticsDB;
use PDO;

class TestStatisticsMySqlPDO implements TestStatisticsDB
{

    private $testData;
    private $askedQuestionsCount;
    private $knownQuestionsCount;
    private $unknownQuestionsCount;
    private $knownQuestionsPercent;
    private $unknownQuestionsPercent;
    private $testCount;


    public function __construct( PDO $conn, string $userName )
    {
        $this->testData = $this->queryTestData( $conn, $userName );
        $this->askedQuestionsCount = $this->sumAskedQuestionsCount();
        $this->knownQuestionsCount = $this->sumKnownQuestionsCount();
        $this->unknownQuestionsCount = $this->askedQuestionsCount - $this->knownQuestionsCount;
        if( $this->askedQuestionsCount !== 0 ){
            $this->knownQuestionsPercent = $this->knownQuestionsCount / $this->askedQuestionsCount * 100;
            $this->unknownQuestionsPercent = $this->unknownQuestionsCount / $this->askedQuestionsCount * 100;
        }else{
            $this->knownQuestionsPercent = 0;
            $this->unknownQuestionsPercent = 0;
        }
        $this->testCount = count( $this->testData );
    }
    
    public function getTestData(): array
    {
        return $this->testData;
    }

    public function getAskedQuestionsCount():int
    {
        return $this->askedQuestionsCount;
    }

    public function getKnownQuestionsCount():int
    {
        return $this->knownQuestionsCount;
    }

    public function getUnknownQuestionsCount():int
    {
        return $this->unknownQuestionsCount;
    }

    public function getKnownQuestionsPercent():float
    {
        return $this->knownQuestionsPercent;
    }

    public function getUnknownQuestionsPercent():float
    {
        return $this->unknownQuestionsPercent;
    }
    
    public function getTestCount():int
    {
        return $this->testCount;
    }
    
    private function queryTestData( PDO $conn, string $userName ):array
    {
        $result = array();
        $sql = 'select kerdesek_szama_tesztenkent.kerdesek_szama, '
            . 'case when sikeres_valaszok_szama_tesztenkent.sikeres_valaszok_szama is null then 0 else sikeres_valaszok_szama_tesztenkent.sikeres_valaszok_szama end as sikeres_valaszok_szama, '
            . 'kerdesek_szama_tesztenkent.kitoltes_datuma,  kerdesek_szama_tesztenkent.forrasnyelv, kerdesek_szama_tesztenkent.celnyelv from '
            . '(select count(teszt.kitoltes_datuma) as kerdesek_szama, teszt.kitoltes_datuma, teszt.forrasnyelv, teszt.celnyelv from '
            . 'teszt inner join teszt_szavai on teszt.teszt_id = teszt_szavai.teszt_id and teszt.felhasznalonev = :felhasznalonev group by (teszt.kitoltes_datuma)) as kerdesek_szama_tesztenkent '
            . 'left join '
            . '(select count( teszt.kitoltes_datuma ) as sikeres_valaszok_szama, teszt.kitoltes_datuma from '
            . 'teszt inner join teszt_szavai on teszt.teszt_id = teszt_szavai.teszt_id and teszt.felhasznalonev = :felhasznalonev where teszt_szavai.sikeres_valasz = true group by ( teszt.kitoltes_datuma )) as sikeres_valaszok_szama_tesztenkent '
            . 'on kerdesek_szama_tesztenkent.kitoltes_datuma = sikeres_valaszok_szama_tesztenkent.kitoltes_datuma';
            $stmt = $conn->prepare( $sql );
            $stmt->bindParam( ":felhasznalonev", $userName );
            if( $stmt->execute() ){
                while( ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) !== false ){
                    array_push( $result, $row );
                }
            }
            return $result;
    }
    
    private function sumAskedQuestionsCount(): int
    {
        $sum = 0;
        foreach( $this->testData as $test ){
            $sum += $test['kerdesek_szama'];
        }
        return $sum;
    }
    
    private function sumKnownQuestionsCount(): int
    {
        $sum = 0;
        foreach( $this->testData as $test ){
            $sum += $test['sikeres_valaszok_szama'];
        }
        return $sum;
    }
    
}

