<?php

namespace App;


abstract class Config
{
    /** @var string string */
    const DB_HOST = 'localhost';

    /** @var string string */
    const DB_PORT = '3306';

    /** @var string string */
    const DB_USER = 'root';

    /** @var string string */
    const DB_PASSWORD = '';

    /** @var string string */
    const DB_NAME = 'via_sms';

    /** @var string string */
    const DB_CHARSET = 'UTF-8';

    const UPLOADS_DIR = 'public/uploads/';
}