<?php
namespace controller\classes;

use environment\Environment;

Environment::initEnvironment();

class Logger
{
    
    public static function log( string $message ):void
    {
        $logFilePath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . Environment::getLogFileName();
        if( ($logFile = fopen( $logFilePath, 'a' )) !== false ){
            fputs( $logFile, date('Y.m.d. H:i:s') . " - " . $message . PHP_EOL );
        }        
    }
    
}

