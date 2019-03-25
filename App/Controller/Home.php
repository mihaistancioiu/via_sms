<?php

namespace App\Controller;

use App\Services\StatementService;
use Core\Controller;
use Core\View;

class Home extends Controller
{
    public function index()
    {
        $statement = new StatementService();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $statement->isValidStatement($this->requestParams, $this->requestFiles)) {
            $content = $statement->processStatement($this->requestFiles, $this->requestParams['bankId']);

            return View::render('Home\view.php', [
                'content' => $content,
            ]);
        }

        return View::render('Home\index.php',
            array(
                'banksInfo' => $statement->getBanksInfo(),
                'selectedBank' => !empty($this->requestParams['bankId']) ? $this->requestParams['bankId'] : null,
                'errors' => !empty($statement->validator->getErrors()) ? $statement->validator->getErrors() : array(),
            )
        );
    }

    public function save()
    {
        $statement = new StatementService();

        $status = $statement->save($this->requestParams);

        return View::render('Home\save.php', [
            'status' => $status,
        ]);
    }
}