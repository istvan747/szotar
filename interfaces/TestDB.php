<?php
namespace interfaces;

use modell\TestValidator;

Interface TestDB
{
    
    public function saveTest( TestValidator $testValidator ):bool;    

}

