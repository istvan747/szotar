<?php
namespace modell;


use interfaces\MeaningJSONList;
use controller\classes\MeaningArrayList;

class TestValidator
{
    
    private $test;
    private $answers;
    private $badAnswers;
    private $goodAnswers;
    private $testValidationSuccess;
    
    public function __construct( Test $test, MeaningJSONList $answers ){
        $this->test = $test;
        $this->answers = $answers;
        $this->testValidationSuccess = false;
        $this->badAnswers = new MeaningArrayList();
        $this->goodAnswers = new MeaningArrayList();
        $this->validateTest();
    }
    
    public function getUserName():string
    {
        return $this->test->getUserName();
    }
    
    public function getSourceLanguage():string
    {
        return $this->test->getSourceLanguage();
    }
    
    public function getTargetLanguage():string
    {
        return $this->test->getTargetLanguage();
    }
    
    public function testValidationSuccess(){
        return $this->testValidationSuccess;
    }
    
    public function getGoodAnswers():MeaningJSONList
    {
        return $this->goodAnswers;
    }
    
    public function getBadAnswers():MeaningJSONList
    {
        return $this->badAnswers;
    }
    
    public function getGoodAnswersCount():int
    {
        return $this->goodAnswers->getElementCount();
    }
    
    public function getBadAnswersCount():int
    {
        return $this->badAnswers->getElementCount();
    }

    private function validateTest():void
    {
        if( $this->test !== null ){
            $testMeanings = $this->test->getTestQuestionsInMeaningJSONList();
            foreach( $testMeanings as $meaning ){
                if( $this->answersContainsTestMeaning($meaning) ){
                    $this->goodAnswers->addMeaning( $meaning );
                }else{
                    $this->badAnswers->addMeaning( $meaning );
                }
            }
            $this->testValidationSuccess = true;
        }
    }
    
    private function answersContainsTestMeaning( Meaning $meaning ):bool
    {
        foreach( $this->answers as $answer ){
            if( $meaning->equals($answer))
                return true;
        }
        return false;
    }
   
    
}

?>
