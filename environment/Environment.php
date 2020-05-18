<?php
namespace environment;

class Environment
{
    
    private static $db_hostName;
    private static $db_port;
    private static $db_databaseName;
    private static $db_userName;
    private static $db_password;
    private static $languageTableName = array();
    private static $initSuccess = false;
    private static $userNameMaxLength;
    private static $userNameMinLength;
    private static $passwordMaxLength;
    private static $passwordMinLength;
    private static $emailMaxLength;
    private static $emailMinLength;
    private static $wordMaxLength;
    private static $wordMinLength;
    private static $topicMinLength;
    private static $topicMaxLength;
    private static $wordClassMinLength;
    private static $wordClassMaxLength;
    private static $emailRegex;
    private static $userNameRegex;
    private static $passwordRegex;
    private static $wordRegex;
    private static $topicRegex;
    private static $wordClassRegex;
    private static $logFileName;
    private static $minTestQuestions;
    private static $maxTestQuestions;

    public static function initEnvironment(){
        if( !self::$initSuccess ){
            $file = @fopen('..' . DIRECTORY_SEPARATOR . '.env', 'r') or die('Environment read error.');
            while( !feof($file) ){
                $row = explode('=', fgets( $file ));
                switch( trim($row[0]) ){
                    case 'DB_HOST_NAME': self::$db_hostName = trim($row[1]); break;
                    case 'DB_PORT': self::$db_port = trim($row[1]); break;
                    case 'DB_NAME': self::$db_databaseName = trim($row[1]); break;
                    case 'DB_USERNAME': self::$db_userName = trim($row[1]); break;
                    case 'DB_PASSWORD': self::$db_password = trim($row[1]); break;
                    case 'LANGUAGE_MAGYAR_TABLE': self::$languageTableName[trim($row[0])] = trim($row[1]); break;
                    case 'LANGUAGE_ANGOL_TABLE': self::$languageTableName[trim($row[0])] = trim($row[1]); break;
                    case 'USERNAME_MAX_LENGTH': self::$userNameMaxLength = trim($row[1]); break;
                    case 'USERNAME_MIN_LENGTH': self::$userNameMinLength = trim($row[1]); break;
                    case 'PASSWORD_MAX_LENGTH': self::$passwordMaxLength = trim($row[1]); break;
                    case 'PASSWORD_MIN_LENGTH': self::$passwordMinLength = trim($row[1]); break;
                    case 'EMAIL_MAX_LENGTH': self::$emailMaxLength = trim($row[1]); break;
                    case 'EMAIL_MIN_LENGTH': self::$emailMinLength = trim($row[1]); break;                    
                    case 'WORD_MAX_LENGTH': self::$wordMaxLength = trim($row[1]); break;
                    case 'WORD_MIN_LENGTH': self::$wordMinLength = trim($row[1]); break;
                    case 'TOPIC_MIN_LENGTH': self::$topicMinLength = trim($row[1]); break;
                    case 'TOPIC_MAX_LENGTH': self::$topicMaxLength = trim($row[1]); break;
                    case 'WORD_CLASS_MIN_LENGTH': self::$wordClassMinLength = trim($row[1]); break;
                    case 'WORD_CLASS_MAX_LENGTH': self::$wordClassMaxLength = trim($row[1]); break;
                    case 'EMAIL_REGEX': self::$emailRegex = trim($row[1]); break;
                    case 'USERNAME_REGEX': self::$userNameRegex = trim($row[1]); break;
                    case 'PASSWORD_REGEX': self::$passwordRegex = trim($row[1]); break;
                    case 'WORD_REGEX': self::$wordRegex = trim($row[1]); break;
                    case 'TOPIC_REGEX': self::$topicRegex = trim($row[1]); break;
                    case 'WORD_CLASS_REGEX': self::$wordClassRegex = trim($row[1]); break;
                    case 'LOG_FILE': self::$logFileName = trim($row[1]); break;
                    case 'MIN_TEST_QUESTIONS': self::$minTestQuestions = trim($row[1]); break;
                    case 'MAX_TEST_QUESTIONS': self::$maxTestQuestions = trim($row[1]); break;
                }
            }
            date_default_timezone_set("Europe/Budapest");
            self::$initSuccess = true;
        }
    }
    
    public static function getLogFileName():string
    {
        return Environment::$logFileName;
    }
    
    public static function getDBHhostName():string
    {
        return Environment::$db_hostName;
    }

    public static function getDBPport():string
    {
        return Environment::$db_port;
    }

    public static function getDBDatabaseName():string
    {
        return Environment::$db_databaseName;
    }
    
    public static function getDBUserName():string
    {
        return Environment::$db_userName;
    }
    
    public static function getDBPassword():string
    {
        return Environment::$db_password;
    }

    public static function getUserNameMaxLength()
    {
        return Environment::$userNameMaxLength;
    }

    public static function getUserNameMinLength()
    {
        return Environment::$userNameMinLength;
    }

    public static function getPasswordMaxLength()
    {
        return Environment::$passwordMaxLength;
    }

    public static function getPasswordMinLength()
    {
        return Environment::$passwordMinLength;
    }

    public static function getEmailMaxLength()
    {
        return Environment::$emailMaxLength;
    }

    public static function getEmailMinLength()
    {
        return Environment::$emailMinLength;
    }

    public static function getWordMaxLength()
    {
        return Environment::$wordMaxLength;
    }

    public static function getWordMinLength()
    {
        return Environment::$wordMinLength;
    }

    public static function getTopicMinLength()
    {
        return Environment::$topicMinLength;
    }

    public static function getTopicMaxLength()
    {
        return Environment::$topicMaxLength;
    }

    public static function getWordClassMinLength()
    {
        return Environment::$wordClassMinLength;
    }

    public static function getWordClassMaxLength()
    {
        return Environment::$wordClassMaxLength;
    }

    public static function getEmailRegex()
    {
        return Environment::$emailRegex;
    }

    public static function getUserNameRegex()
    {
        return Environment::$userNameRegex;
    }

    public static function getPasswordRegex()
    {
        return Environment::$passwordRegex;
    }

    public static function getWordRegex()
    {
        return Environment::$wordRegex;
    }

    public static function getTopicRegex()
    {
        return Environment::$topicRegex;
    }

    public static function getWordClassRegex()
    {
        return Environment::$wordClassRegex;
    }

    public static function getMinTestQuestions()
    {
        return Environment::$minTestQuestions;
    }

    public static function getMaxTestQuestions()
    {
        return Environment::$maxTestQuestions;
    }

    public static function getLanguageTableName( string $language ):string
    {
        $language = strtolower( trim( $language ));
        switch( $language ){
            case 'magyar': return Environment::$languageTableName['LANGUAGE_MAGYAR_TABLE'];
            case 'angol': return Environment::$languageTableName['LANGUAGE_ANGOL_TABLE'];;
        }
        return '';
    }

    
}

