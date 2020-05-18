<?php
namespace tests\unitTests;
require_once 'autoloader.php';

use PHPUnit\Framework\TestCase;
use modell\Meaning;
use modell\Word;
use modell\Test;
use controller\classes\MeaningArrayList;

final class TestTest extends TestCase
{
    
    public function testGetEmptyTestQuestionsInJSON(){
        $test = new Test('magyar', 'angol');
        $this->assertJson( $test->getTestQuestionsInJSON() );
    }
    
    
    public function testGetTestQuestionsInJSON(){
        $questions = new MeaningArrayList();
        $questions->addMeaning( new Meaning(new Word('apple'), new Word('alma')) );
        $questions->addMeaning( new Meaning(new Word('pear'), new Word('körte')) );
        $test = new Test('angol', 'magyar', $questions );        
        $json = '['
                    . '[{"sourceLanguage":"angol"},{"targetLanguage":"magyar"}],'
                    . '['
                        . '{"wordA":{"id":-1,"word":"apple"},"wordB":{"id":-1,"word":"alma"},"topic":"","wordClass":"","id":-1},'
                        . '{"wordA":{"id":-1,"word":"pear"},"wordB":{"id":-1,"word":"körte"},"topic":"","wordClass":"","id":-1}'
                    . ']'
                . ']';
        $this->assertJsonStringEqualsJsonString($test->getTestQuestionsInJSON(), $json );
    }
    
    public function testSetTestQuestionsByJSON(){
        $json = '['
                    . '[{"sourceLanguage":"angol"},{"targetLanguage":"magyar"}],'
                    . '['
                        . '{"wordA":{"id":-1, "word":"pear"},"wordB":{"id":-1, "word":"körte"},"topic":"","wordClass":"","id":-1}'
                    . ']'
                . ']';
        $test = new Test();
        $test->setTestQuestionsByJSON( $json );
        $this->assertEquals( $test->getSourceLanguage(), 'angol' );
        $this->assertEquals($test->getTargetLanguage(), 'magyar');
        $testMeaning = new Meaning(new Word('pear'), new Word('körte'), '', '');
        $this->assertTrue( $testMeaning->equals( ($test->getTestQuestionsInMeaningJSONList())[0] ) );
    }    
    
}

?>