<?php

namespace App\Models;

use Core\Model;
use mysql_xdevapi\Exception;

class Statement extends Model
{
    /**
     * @param int $bankId
     * @return bool
     */
    public function findAllStatementsByBankId(int $bankId)
    {
        $db = static::getDbInstance();
        $stmt = $db->prepare('SELECT * FROM statements s where s.bankId = ?');
        $stmt->bind_param("i", $bankId);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * @param array $hashList
     * @return mixed
     */
    public function findDuplicateStatementsByHashList(array $hashList, $bankId)
    {
        $db = static::getDbInstance();
        $listWhere = "('" . implode("','", $hashList) . "')";
        $result = $db->query('SELECT hash FROM statements s where s.bank_id = '.$bankId.' and s.hash in ' . $listWhere);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * @param array $row
     * @throws \Exception
     */
    public function save(array $data)
    {
        $db = static::getDbInstance();
        $db->autocommit(FALSE);
        $stmt = $db->prepare('INSERT INTO statements 
                (bank_id, account_number, `name`, surname, amount, `date`, explanation, hash) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        foreach ($data as $row) {
            $stmt->bind_param("isssdsss",
                $row['bank_id'], $row['account'], $row['name'], $row['surname'], $row['amount'], $row['date'], $row['explanation'], $row['hash']);
            if (!$stmt->execute()) {
                $db->rollback();
                throw new \Exception($db->error);
            }
        }

        $db->commit();
    }
}