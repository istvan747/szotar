<?php
namespace tests\unitTests;
require_once 'autoloader.php';

use PHPUnit\Framework\TestCase;
use modell\Word;

final class WordTest extends TestCase
{
    
    public function testWordEqualsStrict(){
       $alma = new Word('alma', 2);
       $alma2 = new Word('alma', 2);
       $this->assertTrue( $alma->equalsStrict( $alma2 ));       
    }
    
    public function testWordNotEqualsStrict(){
        $alma = new Word('alma', 2);
        $korte = new Word('korte', 2);
        $this->assertFalse( $alma->equalsStrict( $korte )); 
    }
    
    public function testWordEquals(){
        $almaFa = new Word('alma fa');
        $almaFaWithSpace = new Word('    AlMa                 Fa');
        $this->assertTrue( $almaFa->equals( $almaFaWithSpace ));
    }
    
    public function testWordNotEquals(){
        $korteFa = new Word('korte fa');
        $almaFaWithSpace = new Word('    AlMa                 Fa');
        $this->assertFalse( $korteFa->equals( $almaFaWithSpace ));
    }
    
}

?>