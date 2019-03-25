<?php

namespace App\Models;

use Core\Model;

class Bank extends Model
{
    /**
     * @return array
     */
    public function findAllBanks(){
        $db = static::getDbInstance();
        $result = $db->query('SELECT id, name, statement_format FROM banks');

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findBankStatementFormatById($bankId){
        $db = static::getDbInstance();
        $stmt = $db->prepare('SELECT statement_format FROM banks b where b.id = ?');
        $stmt->bind_param("i", $bankId);
        $stmt->execute();
        $stmt -> store_result();
        $stmt->bind_result($statementFormat);
        $stmt->fetch();

        return $statementFormat;
    }


}