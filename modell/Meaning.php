<?php
namespace modell;

use function PHPUnit\Framework\stringContains;

class Meaning
{
    
    private $wordA;
    private $wordB;
    private $topic;
    private $wordClass;
    private $id;

    public function __construct( Word $wordA = null , Word $wordB = null , string $topic = '', string $wordClass = '', int $id = -1 ){
        if( $wordA === null ){
            $this->wordA = new Word();
        }else{
            $this->wordA = $wordA;
        }
        if( $wordB === null ){
            $this->wordB = new Word();
        }else{
            $this->wordB = $wordB;
        }
        $this->topic = $topic;
        $this->wordClass = $wordClass;
        $this->id = $id;
    }
    
    public function getJSON():string
    {
        return '{"wordA":' . $this->getWordA()->getJSON() . ',"wordB":' . $this->getWordB()->getJSON()
                . ',"topic":"' . $this->getTopic() . '","wordClass":"' . $this->getWordClass() . '","id":' . $this->getId() . '}';
    }
    
    public function setWithJSON( string $json ):bool
    {
        if( ( $object = json_decode( $json ) ) !== null ){            
            return $this->setWithObject( $object );
        }
        return false;
    }
    
    public function setWithObject( object $object ):bool
    {
        $issetVariables = isset( $object->wordA ) && isset( $object->wordB )
        && isset( $object->topic ) && isset( $object->wordClass )
        && isset( $object->id );
        if( !$issetVariables ){
            return false;
        }
        $typesValid = is_object( $object->wordA ) && is_object( $object->wordB )
        && is_string( $object->topic ) && is_string( $object->wordClass )
        && is_int( $object->id );
        if( !$typesValid ){
            return false;
        }
        if( !$this->getWordA()->setWithObject( $object->wordA ) ){
            return false;
        }
        if( !$this->getWordB()->setWithObject( $object->wordB ) ){
            return false;
        }
        $this->setTopic( $object->topic );
        $this->setWordClass( $object->wordClass );
        $this->setId( $object->id );
        return true;
    }
    
    public function swap(){
        $tmp = $this->wordA;
        $this->wordA = $this->wordB;
        $this->wordB = $tmp;
    }
    
    public function getWordA():Word
    {
        return $this->wordA;
    }
    
    public function getWordB():Word
    {
        return $this->wordB;
    }
    
    public function getTopic():string
    {
        return $this->topic;
    }
    
    public function getWordClass():string
    {
        return $this->wordClass;
    }
    
    public function setWordA( Word $wordA):void
    {
        $this->wordA = $wordA;
    }
    
    public function setWordB( Word $wordB):void
    {
        $this->wordB = $wordB;
    }
    
    public function setTopic( string $topic):void
    {
        $this->topic = $topic;
    }
    
    public function setWordClass( string $wordClass):void
    {
        $this->wordClass = $wordClass;
    }
    
    public function setId( int $id ):void
    {
        $this->id = $id;
    }
    
    public function getId():int
    {
        return $this->id;
    }
    
    public function equals( Meaning $meaning ):bool
    {
        if( $this == $meaning ){
            return true;
        }
        if( $meaning == null ){
            return false;
        }
        if( $meaning instanceof Meaning ){
            if( !$this->wordA->equals($meaning->wordA) ){
                return false;
            }else if( !$this->wordB->equals($meaning->wordB) ){
                return false;
            }else{
                return true;
            }
        }
        return false;
    }
    
    public function equalsStrinct( Meaning $meaning ):bool
    {
        if( $this == $meaning ){
            return true;
        }
        if( $meaning == null ){
            return false;
        }
        if( $meaning instanceof Meaning ){
            if( !$this->wordA->equalsStrict($meaning->wordA) ){
                return false;
            }else if( !$this->wordB->equalsStrict($meaning->wordB) ){
                return false;
            }else if( $this->topic !== $meaning->topic ){
                return false;
            }else if( $this->wordClass !== $meaning->wordClass ){
                return false;
            } if( $this->id !== $meaning->id ){
                return false;
            } else {
                return true;
            }
        }
        return false;
    }
    
    public function __toString():string
    {
        return '[wordA=' . $this->getWordA() . ', wordB=' . $this->getWordB()
                . ', topic=' . $this->getTopic() . ', wordClass=' . $this->getWordClass()
                . ', id=' . $this->getId() . ']';
    }
    
}

?>
