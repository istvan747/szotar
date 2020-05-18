<?php
namespace controller\classes;

use modell\Meaning;
use modell\Word;
use interfaces\MeaningJSONList;

class MeaningArrayList implements MeaningJSONList
{
    private $meaningArray;
    private $arrayIndexcounter;
    private $iteratorCounter;
    
    public function __construct( array $array = array()){
        $this->meaningArray = array();        
        $this->arrayIndexcounter = 0;
        $this->iteratorCounter = 0;
        foreach( $array as $value ){
            if( $value instanceof Meaning ){
                $this->addMeaning( $value );
            }
        }
    }
    
    /*
     * MeaningJSONList 
     * */
    
    public function getElementCount():int
    {
        return count( $this->meaningArray );
    }
    
    public function setListFromJSON( string $json ): bool
    {
        $this->meaningArray = array();
        $this->arrayIndexcounter = 0;
        $this->iteratorCounter = 0;
        if( ($jsonArray = json_decode( $json )) !== null ){
            foreach( $jsonArray as $meaningObject ){
                $tmpMeaning = new Meaning(new Word(), new Word());
                $tmpMeaning->setWithObject( $meaningObject );
                $this->addMeaning( $tmpMeaning );
            }
        }
        return false;
    }
    
    public function getListInJSON(): string
    {
        $meaningCount = $this->getElementCount();
        $count = 0;
        $json = '[';
        foreach( $this as $meaning ){
            $count++;
            $json .= $meaning->getJSON() . (( $count < $meaningCount)?',':'');
        }
        $json .= ']';
        return $json;
    }
    
    public function addMeaning(Meaning $meaning): void
    {
        $this->meaningArray[ $this->arrayIndexcounter ] = $meaning;
        $this->arrayIndexcounter++;
    }
    
    
    /*
     * ArrayAccess
     * */
    
    public function offsetGet($offset)
    {
        if( isset( $this->meaningArray[$offset] ) ){
            return $this->meaningArray[$offset];
        }
        return null;
    }

    public function offsetExists($offset)
    {
        return isset( $this->meaningArray[$offset] );
    }

    public function offsetUnset($offset)
    {
        unset( $this->meaningArray[ $offset] );
    }

    public function offsetSet($offset, $value)
    {
        if( $value instanceof Meaning ){
            $index = $offset;
            if( (empty( $index ) && $index !== 0) || !is_int( $index ) || $index < 0 ){
                $index = $this->arrayIndexcounter;
                $this->arrayIndexcounter++;
            }else if( $index > $this->arrayIndexcounter ){
                $this->arrayIndexcounter = $index + 1;
            }
            $this->meaningArray[$index] = $value;
        }
    }
    
    /*
     * Iterator
     * 
     * */
    
    public function next()
    {
        $this->iteratorCounter++;
        while( $this->iteratorCounter < $this->arrayIndexcounter && !$this->offsetExists( $this->iteratorCounter )  ){
            $this->iteratorCounter++;
        }
        
    }

    public function valid()
    {
        return isset( $this->meaningArray[ $this->iteratorCounter ] );
    }

    public function current()
    {
        return $this->meaningArray[ $this->iteratorCounter];
    }

    public function rewind()
    {
        $this->iteratorCounter = 0;
    }

    public function key()
    {
        return $this->iteratorCounter;
    }

}

