<?php
namespace tests\unitTests;
require_once 'autoloader.php';

use PHPUnit\Framework\TestCase;
use modell\Meaning;
use modell\Word;

final class MeaningTest extends TestCase
{
    
    public function testSwap(){
        $appleMeaning = new Meaning( new Word('alma', 1), new Word('apple', 1), 'gyümölcs', 'főnév');
        $appleMeaning->swap();
        $swappedMeaning = new Meaning( new Word('apple', 1), new Word('alma', 1), 'gyümölcs', 'főnév');
        $this->assertTrue( $appleMeaning->equalsStrinct( $swappedMeaning ));
    }
    
    public function testMeaningEqualsStrict(){
        $appleMeaningA = new Meaning( new Word('alma', 1), new Word('apple', 1), 'gyümölcs', 'főnév');
        $appleMeaningB = new Meaning( new Word('alma', 1), new Word('apple', 1), 'gyümölcs', 'főnév');
        $this->assertTrue( $appleMeaningA->equalsStrinct( $appleMeaningB ));
    }
    
    public function testMeaningNotEqualsStrict(){
        $appleMeaning = new Meaning( new Word('alma', 1), new Word('apple', 1), 'gyümölcs', 'főnév');
        $pearMeaning = new Meaning( new Word('körte', 1), new Word('pear', 1), 'gyümölcs', 'főnév');
        $this->assertFalse( $appleMeaning->equalsStrinct( $pearMeaning ));
    }
    
    public function testMeaningEquals(){
        $appleMeaningA = new Meaning( new Word('alma'), new Word('apple'), 'gyümölcs', 'főnév');
        $appleMeaningB = new Meaning( new Word('  ALM A'), new Word('AppLe  '), 'gyümölcs', 'főnév');
        $this->assertTrue( $appleMeaningA->equals( $appleMeaningB ));
    }
    
    public function testMeaningNotEquals(){
        $appleMeaning = new Meaning( new Word('alma', 1), new Word('apple', 1), 'gyümölcs', 'főnév');
        $pearMeaning = new Meaning( new Word('körte', 1), new Word('pear', 1), 'gyümölcs', 'főnév');
        $this->assertFalse( $appleMeaning->equals( $pearMeaning ));
    }
    
}

?>