<?php
namespace modell;

use controller\classes\MeaningArrayList;
use interfaces\MeaningJSONList;

class Test
{
    
    private $testQuestions;
    private $sourceLanguage;
    private $targetLanguage;
    private $userName;

    public function __construct( string $sourceLanguage = '', string $targetLanguage = '', MeaningJSONList $testQuestions = null, string $userName = '' )
    {
        $this->sourceLanguage = $sourceLanguage;
        $this->targetLanguage = $targetLanguage;
        if( $testQuestions === null ){
            $this->testQuestions = new MeaningArrayList();
        }else{
            $this->testQuestions = $testQuestions;
        }
        $this->userName = $userName;        
    }
    
    public function getTestQuestionsInJSON():string
    {       
        $json = '['        
                    . '['
                        . '{"sourceLanguage":"' . $this->getSourceLanguage() . '"},'
                        . '{"targetLanguage":"' . $this->getTargetLanguage() . '"}'
                    . '],'      
                    . $this->testQuestions->getListInJSON()
                . ']';
        return $json;
    }
    
    public function setTestQuestionsByJSON( string $testQuestions ):bool
    {
        $json_array = json_decode( $testQuestions );
        if( $json_array !== null && is_array( $json_array ) && count( $json_array ) == 2 ){
            $this->testQuestions = null;
            $this->testQuestions = new MeaningArrayList();
            $languages = $json_array[0];
            $questions = $json_array[1];
            if( is_array( $languages ) && count( $languages ) > 0 ){
                if( isset( $languages[0]->sourceLanguage ) ){
                    $this->setSourceLanguage( $languages[0]->sourceLanguage );
                }
                if( isset( $languages[1]->targetLanguage ) ){
                    $this->setTargetLanguage( $languages[1]->targetLanguage );
                }
            }
            
            if( is_array( $questions ) && count( $questions ) > 0 ){
                
                foreach( $questions as $meaningObject ){
                    $newMeaning = new Meaning();
                    if( $newMeaning->setWithObject( $meaningObject ) ){
                        $this->testQuestions->addMeaning( $newMeaning );
                    }
                }

            }
        }
        return false;
    }
    
    public function getUserName():string
    {
        return $this->userName;
    }
    
    public function setUserName( String $userName ):void
    {
        $this->userName = $userName;
    }

    public function getTestQuestionsInMeaningJSONList():MeaningJSONList
    {
        return $this->testQuestions;
    }
    
    public function getSourceLanguage():string
    {
        return $this->sourceLanguage;
    }
    
    public function getTargetLanguage():string
    {
        return $this->targetLanguage;
    }
    
    public function setTestQuestionsByMeaningJSONList( MeaningJSONList $testQuestions ):void
    {
        $this->testQuestions = $testQuestions;
    }
    
    public function setSourceLanguage( string $sourceLanguage ):void
    {
        $this->sourceLanguage = $sourceLanguage;
    }
    
    public function setTargetLanguage( string $targetLanguage ):void
    {
        $this->targetLanguage = $targetLanguage;
    }
    
}

