<?php
namespace interfaces;

use modell\Test;

interface TestGenerator
{
    
    public function getRandomTestByTopic( array $topic, string $sourceLanguege, string $targetLanguage, int $questionCount ):Test;
    
    public function getMostOfTimeSpoiledTest( string $sourceLanguege, string $targetLanguage, int $questionCount ):Test;
    
    public function getOldestAskedTest( string $sourceLanguege, string $targetLanguage, int $questionCount ):Test;
    
    public function getLeastFrequentlyAskedTest( string $sourceLanguege, string $targetLanguage, int $questionCount ):Test;
    
}

