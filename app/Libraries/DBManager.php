<?php

namespace App\Libraries;

use Config\Database;

class DBManager
{
    protected static $db = null;

    // Inicializa la conexión a la base de datos
    public static function init($databaseName)
    {
        $config = [
            'DSN'      => '',
            'hostname' => 'localhost',
            'username' => 'uxjnphmcle2im',
            'password' => '41$w^$31@@[2',
            'database' => $databaseName, // la DB es dinámica
            'DBDriver' => 'MySQLi',
            'DBPrefix' => '',
            'pConnect' => false,
            'DBDebug'  => (ENVIRONMENT !== 'production'),
            'charset'  => 'utf8mb4',
            'DBCollat' => 'utf8mb4_general_ci',
            'swapPre'  => '',
            'encrypt'  => false,
            'compress' => false,
            'strictOn' => false,
            'failover' => [],
            'port'     => 3306,
        ];

        self::$db = \Config\Database::connect($config);
    }


    // Devuelve la conexión actual
    public static function getDB()
    {
        return self::$db;
    }
}
