<?php

namespace Core;

use App\Config;

class Model
{
    private static $dbInstance;

    /**
     * @return \mysqli|void
     */
    protected static function getDbInstance()
    {
        if (empty(self::$dbInstance)) {
            try {
                self::$dbInstance = mysqli_connect(
                    Config::DB_HOST,
                    Config::DB_USER,
                    Config::DB_PASSWORD,
                    Config::DB_NAME,
                    Config::DB_PORT
                );

                self::$dbInstance->query('SET NAMES ' . Config::DB_CHARSET);
                self::$dbInstance->query('SET CHARACTER SET ' . Config::DB_CHARSET);
            } catch (\Exception $error) {
               error_log($error->getMessage());

                return;
            }
        }

        return self::$dbInstance;
    }

}
