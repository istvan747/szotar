<?php
namespace controller\classes;

use database\MySqlDatabasePDOConnection;
use database\MeaningMySqlPDO;
use database\WordMySqlPDO;
use PDOException;
use Exception;
use Error;

class TopicGroupSelectionField
{
    
    public static function getTopicGroupSelectionField():string
    {
        $selectField = '';
        try{
            
            $conn = (new MySqlDatabasePDOConnection())->getConnection();
            $meaningDB = new MeaningMySqlPDO($conn, new WordMySqlPDO($conn));
            $topicList = $meaningDB->getTopicGroup();
            if( count( $topicList) > 0 ){
                $selectField = '<select name="topic_select_list" id="topic_select_list" multiple ><option value="alltopic" selected >összes csoport</option>';
                foreach( $topicList as $topic ){
                    $selectField .= '<option value=' . str_replace( ' ', '_', $topic ) . ' >' . $topic . '</option>';
                }
                $selectField .= '</select>';
                return $selectField;
            }
            
        }catch( PDOException $e ){
            Logger::log( $e->getCode() . ', ' . $e->getMessage() . ', ' . $e->getFile() . ', ' . $e->getLine() . ', ' . $e->getTraceAsString() );
        }catch( Exception $e ){
            Logger::log( $e->getCode() . ', ' . $e->getMessage() . ', ' . $e->getFile() . ', ' . $e->getLine() . ', ' . $e->getTraceAsString() );
        }catch( Error $e ){
            Logger::log( $e->getCode() . ', ' . $e->getMessage() . ', ' . $e->getFile() . ', ' . $e->getLine() . ', ' . $e->getTraceAsString() );
        }finally{
            $conn = null;
        }
        if( $selectField === '' ){
            $selectField = '<select name="topic_select_list" id="topic_select_list" multiple ><option value="alltopic" selected >összes csoport</option></select>';
        }
        return $selectField;
    }
    
}

