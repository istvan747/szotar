<?php
namespace interfaces;

use modell\Meaning;
use modell\Word;

interface MeaningDB
{
    
    public function saveMeaning( string $sourceLanguage, string $targetLanguage, Meaning $meaning ):bool;
    
    public function getMeaningByWordString( string $language, string $word ):MeaningJSONList;
    
    public function getMeaningByWordObyect( Word $word ):Meaning;
    
    public function getMeaningById( int $id ):Meaning;
    
    public function getMeaningContainString( string $str ):MeaningJSONList;
    
    public function getMeaningByTopic( string $topic ):MeaningJSONList;
    
    public function getMeaningByWordClass( string $wordClass ):MeaningJSONList;
    
    public function filterMeaningByFields( string $wordContent, string $topic, string $word_class, int $limit ):MeaningJSONList;
    
    public function updateMeaningById( int $id, Meaning $meaning ):bool;
    
    public function deleteMeaning( Meaning $meaning ):bool;
    
    public function deleteMeaningById( int $id ):bool;
    
    public function getTopicGroup():array;
    
}

?>