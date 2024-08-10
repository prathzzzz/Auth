<?php
session_start(); //sabse phele yeh file load hogi iske liye iski first line pe
$_SESSION['key'] = 'value';

$app = __DIR__; //POINTS TO CURRENT DIRECTORY
require_once("{$app}/../vendor/autoload.php");

require_once("{$app}/helper/functions.inc.php");

require_once("{$app}/classes/AppConfig.class.php");
require_once("{$app}/classes/Hash.class.php");
require_once("{$app}/classes/DatabaseConnection.class.php");
require_once("{$app}/classes/ErrorHandler.class.php");
require_once("{$app}/classes/Validator.class.php");
require_once("{$app}/classes/QueryBuilder.class.php");
require_once("{$app}/classes/User.class.php");
require_once("{$app}/classes/Auth.class.php");
require_once("{$app}/classes/Token.class.php");
require_once("{$app}/classes/Mail.class.php");

$config = AppConfig::getInstance();
$connection = new DatabaseConnection($config);  //injecting dependency

$errorHandler = new ErrorHandler();

$validator = new Validator($connection, $config->APP_DEBUG, $errorHandler);

User::migrate($connection, $config->APP_DEBUG);
Token::migrate($connection, $config->APP_DEBUG);
