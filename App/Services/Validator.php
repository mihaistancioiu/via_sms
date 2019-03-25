<?php

namespace App\Services;

class Validator
{
    /** @var array */
    private $errors = array();

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }


    private function addError($errorMsg)
    {
        $this->errors[] = $errorMsg;
    }

    public function hasErrors()
    {

        return count($this->errors) == 0;
    }


    public function validateStatementType(string $bankStatementFormat, string $extension)
    {
        if(strtolower($bankStatementFormat) == strtolower($extension)){

            return true;
        }

        $this->addError('Invalid file format!');

        return false;
    }

}