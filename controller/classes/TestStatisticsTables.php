<?php
namespace controller\classes;

use PDO;
use database\TestStatisticsMySqlPDO;

class TestStatisticsTables
{
    
    private $conn;
    private $testStatistics;
    private $userName;
    
    public function __construct( PDO $conn, string $userName )
    {
        $this->conn = $conn;
        $this->userName = $userName;
        $this->testStatistics = new TestStatisticsMySqlPDO( $this->conn, $this->userName );
    }
    
    public function printFilledOutTestsTable(){
        $testResults = $this->testStatistics->getTestData();
        $table = '<table id="test_results_list" >'
                . '<tr>'
                . '<th>dátum</th>'
                . '<th>forrásnyelv</th>'
                . '<th>célnyelv</th>'
                . '<th>kérdések száma</th>'
                . '<th>jó válaszok</th>'
                . '<th>rossz válaszok</th>'
                . '<th>teljesítmény</th>'
                . '</tr>';
        foreach( $testResults as $testResoult ){
            $table .= '<tr>'
                    . '<td>' . $testResoult['kitoltes_datuma'] . '</td>'
                    . '<td>' . $testResoult['forrasnyelv'] . '</td>'
                    . '<td>' . $testResoult['celnyelv'] . '</td>'
                    . '<td>' . $testResoult['kerdesek_szama'] . '</td>'
                    . '<td>' . $testResoult['sikeres_valaszok_szama'] . '</td>'
                    . '<td>' . ( $testResoult['kerdesek_szama'] - $testResoult['sikeres_valaszok_szama'] ) . '</td>'
                    . '<td>' . round( ( $testResoult['sikeres_valaszok_szama'] / $testResoult['kerdesek_szama'] ) * 100, 2 ) . ' %</td>'
                    . '</tr>';
        }
        $table .= '</table>';
        echo $table;
    }
    
    public function printAggregatedData()
    {
        $table = '<table id="aggregated_test_results">'
                . '<tr><th colspan="2" >Összesített teszt adatok</th></tr>'
                . '<tr><th>tesztek száma</th><td>' . $this->testStatistics->getTestCount() . '</td></tr>'
                . '<tr><th>kérdések száma</th><td>' . $this->testStatistics->getAskedQuestionsCount() . '</td></tr>'
                . '<tr><th>helyes válaszok száma</th><td>' . $this->testStatistics->getKnownQuestionsCount() . '</td></tr>'
                . '<tr><th>helytelen válaszok száma</th><td>' . $this->testStatistics->getUnknownQuestionsCount() . '</td></tr>'
                . '<tr><th>helyes válaszok aránya</th><td>' . round( $this->testStatistics->getKnownQuestionsPercent(), 2 ) . ' %</td></tr>'
                . '<tr><th>helytelen válaszok aránya</th><td>' . round( $this->testStatistics->getUnknownQuestionsPercent(), 2 ) . ' %</td></tr>'
                . '</table>';
        echo $table;
    }
    
}

