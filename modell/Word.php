<?php
namespace modell;

class Word
{
  
    private $word;
    private $id;

    public function __construct( string $word = '', int $id = -1 ){
        $this->word = $word;
        $this->id = $id;
    }
    
    public function getJSON():string
    {
        return '{"id":' . $this->getId() . ',"word":"' . $this->getWord() . '"}';
    }
    
    public function setWithJSON( string $json ):bool
    {   
        if( ($object = json_decode($json)) !== null ){
            return $this->setWithObject($object);           
        }
        return false;
    }
    
    public function setWithObject( object $object ):bool
    {
        if( isset( $object->id ) && isset( $object->word ) && is_int( $object->id ) && is_string( $object->word ) ){
            $this->setId( $object->id );
            $this->setWord( $object->word );
            return true;
        }
        return false;
    }

    public function getWord():string
    {
        return $this->word;
    }
    
    public function getId():int
    {
        return $this->id;
    }
    
    public function setWord(string $word):void
    {
        $this->word = $word;
    }
    
    public function setId(int $id):void
    {
        $this->id = $id;
    }

    public function equals( Word $word ):bool
    {
        if( $this == $word ){
            return true;
        }
        if( $word == null ){
            return false;
        }
        if( $word instanceof Word ){
            if( $this->conceptualEquals( $this->word, $word->word ) ){
                return true;
            }
        }
        return false;
    }
    
    public function equalsStrict( Word $word ):bool
    {
        if( $this == $word ){
            return true;
        }
        if( $word == null ){
            return false;
        }
        if( $word instanceof Word ){
            if( $this->word !== $word->word ){
                return false;
            }else if( $this->id !== $word->id ){
                return false;
            }else{
                return true;
            }
        }
        return false;
    }
    
    private function conceptualEquals( string $wordA, string $wordB ):bool
    {
        $strA = strtolower( str_replace(' ', '', $wordA ) );
        $strB = strtolower( str_replace(' ', '', $wordB ) );
        return $strA === $strB;
    }
    
    public function __toString():string
    {
        return '[id='. $this->id . ', word=' . $this->word . ']';
    }
    
}

?>
