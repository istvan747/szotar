<?php
namespace interfaces;

interface TestStatisticsDB
{    
    
    public function getTestData():array;
    
    public function getAskedQuestionsCount():int;
    
    public function getKnownQuestionsCount():int;
    
    public function getUnknownQuestionsCount():int;
    
    public function getKnownQuestionsPercent():float;
    
    public function getUnknownQuestionsPercent():float;
    
    public function getTestCount():int;
    
}

