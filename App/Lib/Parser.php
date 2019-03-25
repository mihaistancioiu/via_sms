<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 3/24/2019
 * Time: 8:49 PM
 */

namespace App\Lib;


class Parser
{
    private $parser;

    /** @var string */
    private $content;

    public function setParserType(ParserInterface $parserType)
    {
        $this->parser = $parserType;
    }

    public function setContent($content){
        $this->content = $content;
    }

    public function loadParsedContent()
    {
        return $this->parser->parse($this->content);
    }
}