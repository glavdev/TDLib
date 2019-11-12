<?php

/**
 * Возвращает соединение с БД
 *
 * @return PDO
 */
function getDB(): PDO
{
    //подключение к БД
    static $db;

    if (isset($db)) {
        return $db;
    }

    $db = new PDO(
        "mysql:dbname={$GLOBALS['config']['dbname']};host={$GLOBALS['config']['dbhost']}",
        $GLOBALS['config']['username'],
        $GLOBALS['config']['password']
    );

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //по умолчанию режим вборки - в виде ассоциативного массива
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $db->exec('SET NAMES utf8');

    return $db;
}