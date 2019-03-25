<?php

namespace App\Services;

use App\Config;
use App\Lib\Parser;
use App\Models\Bank;
use App\Models\Statement;

class StatementService
{
    /** @var Bank */
    private $bankModel;

    /** @var Statement Statement */
    private $statementModel;

    /** @var Validator */
    public $validator;

    public function __construct()
    {
        $this->bankModel = new Bank();
        $this->statementModel = new Statement();
        $this->validator = new Validator();
    }

    /**
     * @return array
     */
    public function getBanksInfo()
    {

        return $this->bankModel->findAllBanks();
    }

    /**
     * @param array $post
     * @param array $files
     * @return bool
     */
    public function isValidStatement(array $request, array $file)
    {
        $pathParts = pathinfo($file['statement']['name']);
        $fileExtension = $pathParts['extension'];
        $bankStatementFormat = $this->bankModel->findBankStatementFormatById($request['bankId']);
        $this->validator->validateStatementType($bankStatementFormat, $fileExtension);

        return $this->validator->hasErrors();
    }

    /**
     * @param array $file
     * @param int $bankId
     * @return array
     */
    public function processStatement(array $file, int $bankId)
    {
        if (!$uploadedFilePath = $this->uploadStatement($file)) {

            return array();
        }

        $content = file_get_contents($uploadedFilePath);
        $content = mb_convert_encoding($content, 'UTF-8',
            mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));

        $pathParts = explode('.', $uploadedFilePath);
        $fileExtension = end($pathParts);
        $className = 'App\Lib\\' . ucfirst($fileExtension) . 'Parser';

        $parser = new Parser();
        $parser->setParserType(new $className);
        $parser->setContent($content);
        $parsedContent = $parser->loadParsedContent();
        $duplicates = $this->statementModel->findDuplicateStatementsByHashList(array_keys($parsedContent), $bankId);

        return $this->formatResponse($parsedContent, $duplicates, $bankId);
    }

    /**
     * @param array $parsedContent
     * @param array $duplicates
     * @param int $bankId
     * @return array
     */
    private function formatResponse(array $parsedContent, array $duplicates, int $bankId)
    {
        $duplicates = array_map(function ($duplicate) {
            return $duplicate['hash'];
        }, $duplicates);

        foreach ($parsedContent as $key => &$value) {
            if ($value['amount'] < 0) {
                unset($parsedContent[$key]);
            }
            $value['is_duplicate'] = false;
            $value['bank_id'] = $bankId;
            if (in_array($key, $duplicates)) {
                $value['is_duplicate'] = true;
            }
        }

        return $parsedContent;
    }


    /**
     * @param array $file
     * @return string
     */
    private function uploadStatement(array $file)
    {
        $targetDir = Config::UPLOADS_DIR;
        $pathParts = pathinfo($file['statement']['name']);
        $fileName = $pathParts['filename'];
        $fileExtension = $pathParts['extension'];
        $targetFile = $targetDir . $fileName . '_' . microtime() . '.' . $fileExtension;

        if (move_uploaded_file($file['statement']["tmp_name"], $targetFile)) {
            return $targetFile;
        }

        return '';
    }

    /**
     * @param array $data
     * @return bool
     */
    public function save(array $data)
    {
        if(!isset($data['add'])){

            return false;
        }

        $formatedData = array_map(function ($row) {
            return unserialize($row);
        }, $data['add']);

        try {
                $this->statementModel->save($formatedData);
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return false;
        }

        return true;
    }

}