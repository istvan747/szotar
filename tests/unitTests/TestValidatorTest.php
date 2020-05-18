<?php
namespace tests\unitTests;

require_once 'autoloader.php';

use PHPUnit\Framework\TestCase;
use modell\Word;
use modell\Meaning;
use modell\Test;
use modell\TestValidator;
use controller\classes\MeaningArrayList;


final class TestValidatorTest extends TestCase
{
    
    public function testTestValidationSuccessTrue(){
        $apple = new Word('apple');
        $alma = new Word('alma');
        $pear = new Word('pear');
        $korte = new Word('korte');
        $table = new Word('table');
        $asztal = new Word('asztal');
        $meaningList = new MeaningArrayList();
        $meaningList->addMeaning( new Meaning( $apple, $alma, 'gyümölcs', 'főnév') );
        $meaningList->addMeaning( new Meaning( $pear, $korte, 'gyümölcs', 'főnév') );
        $meaningList->addMeaning( new Meaning( $table, $asztal, 'tárgy', 'főnév') );
        $test = new Test( 'angol', 'magyar', $meaningList );
        $answers = $meaningList;
        $testValidator = new TestValidator($test, $answers);
        $this->assertTrue( $testValidator->testValidationSuccess());
        
    }
    
    public function testGetGoodAnswers(){
        $apple = new Word('apple');
        $badApple = new Word('appleeee');
        $alma = new Word('alma');
        $pear = new Word('pear');
        $korte = new Word('korte');
        $table = new Word('table');
        $asztal = new Word('asztal');
        $rosszAsztal = new Word('asztallala');       
        
        $goodApple = new Meaning( $apple, $alma, 'gyümölcs', 'főnév');
        $goodPear = new Meaning( $pear, $korte, 'gyümölcs', 'főnév');
        $goodTable = new Meaning( $table, $asztal, 'tárgy', 'főnév');
        $badApple = new Meaning( $badApple, $alma, 'gyümölcs', 'főnév');
        $badTable = new Meaning( $table, $rosszAsztal, 'tárgy', 'főnév');
        
        $questions = new MeaningArrayList();
        $questions->addMeaning( $goodApple );
        $questions->addMeaning( $goodPear );
        $questions->addMeaning( $goodTable );
        $test = new Test( 'angol', 'magyar', $questions );
        
        // rossz válaszok beállítása, csak a 'pear - KÖRTE' szó jó, az 'apple - almaa', 'table - asztall' rossz
        $answers = new MeaningArrayList();
        $answers->addMeaning( $badApple );
        $answers->addMeaning( $goodPear );
        $answers->addMeaning( $badTable );
        
        $testValidator = new TestValidator($test, $answers);
        $goodAnswers = $testValidator->getGoodAnswers();
        $this->assertCount( 1, $goodAnswers );
        $this->assertTrue( $goodPear->equals( $goodAnswers[0] ));
    }
    
    public function testGetBadAnswers(){
        $apple = new Word('apple');
        $badApple = new Word('appleeee');
        $alma = new Word('alma');
        $pear = new Word('pear');
        $korte = new Word('korte');
        $table = new Word('table');
        $asztal = new Word('asztal');
        $rosszAsztal = new Word('asztallala');
        
        $goodApple = new Meaning( $apple, $alma, 'gyümölcs', 'főnév');
        $goodPear = new Meaning( $pear, $korte, 'gyümölcs', 'főnév');
        $goodTable = new Meaning( $table, $asztal, 'tárgy', 'főnév');
        $badApple = new Meaning( $badApple, $alma, 'gyümölcs', 'főnév');
        $badTable = new Meaning( $table, $rosszAsztal, 'tárgy', 'főnév');
        
        $questions = new MeaningArrayList();
        $questions->addMeaning( $goodApple );
        $questions->addMeaning( $goodPear );
        $questions->addMeaning( $goodTable );
        $test = new Test( 'angol', 'magyar', $questions );
        
        // rossz válaszok beállítása, csak a 'pear - KÖRTE' szó jó, az 'apple - almaa', 'table - asztall' rossz
        $answers = new MeaningArrayList();
        $answers->addMeaning( $badApple );
        $answers->addMeaning( $goodPear );
        $answers->addMeaning( $badTable );
        
        $testValidator = new TestValidator($test, $answers);
        $badAnswers = $testValidator->getBadAnswers();
        $this->assertCount( 2, $badAnswers );
        $this->assertTrue( $goodApple->equals( $badAnswers[0] ));
        $this->assertTrue( $goodTable->equals( $badAnswers[1] ));

    }
    
}

?>