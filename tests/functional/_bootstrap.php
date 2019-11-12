<?php
// Here you can initialize variables that will be available to your tests
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

// Подмена БД
$GLOBALS['config']['dbname'] = 'tdintegration_functional_tests';
$GLOBALS['config']['dbhost'] = 'dbhost';
$GLOBALS['config']['username'] = 'tdintegration';
$GLOBALS['config']['password'] = 'tdintegration';
