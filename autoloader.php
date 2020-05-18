<?php 

function autoloader( $class )
{
    $segments = explode('\\', $class);
    $file = __DIR__ . DIRECTORY_SEPARATOR . implode( DIRECTORY_SEPARATOR, $segments ) . '.php';
    if( file_exists($file) )
        include_once( $file );
}

spl_autoload_register( 'autoloader' );

?>