<?php
namespace interfaces;

use modell\Meaning;
use ArrayAccess;
use Iterator;

interface MeaningJSONList extends ArrayAccess, Iterator
{
    
    public function getListInJSON():string;
    
    public function setListFromJSON( string $json ):bool;
    
    public function addMeaning( Meaning $meaning ):void;    
    
}

