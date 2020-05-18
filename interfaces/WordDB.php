<?php
namespace interfaces;

use modell\Word;

interface WordDB
{    
    public function saveWord( string $language, Word $word ):bool;
    
    public function issetWord( string $language, string $word ):bool;
    
    public function getWord( string $language, Word $word ):Word;
    
    public function getWordByWordString( string $language, string $word ):Word;
    
    public function getWordById( string $language, int $id ):Word;
    
    public function getWordContainsString( string $language, string $str ):array;
    
    public function updateWord( string $language, Word $oldWord, Word $newWord ):bool;
    
    public function deleteWordByWordString( string $language, string $word ):bool;
    
    public function deleteWordById( string $language, int $id ):bool;
    
    public function deleteWord( string $language, Word $word ):bool;
    
}

