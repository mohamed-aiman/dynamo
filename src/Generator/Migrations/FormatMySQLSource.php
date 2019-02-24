<?php

namespace Generator\Migrations;

use Generator\Helpers\Helper;
use SqlFormatter;

class FormatMySQLSource
{
    use Helper;

    protected $sourceFile;
    protected $fileContents;
    protected $formattedSource;


    public function __construct($sourceFile = null)
    {
        $this->formatter = new SqlFormatter;
        $this->sourceFile = $this->getSourceFile();
        $this->format();
    }

    protected function getSourceFile()
    {
        return file_get_contents(__DIR__ . '/../../../mysql.sql');
    }

    protected function format()
    {
        $commentsRemoved = $this->clean();
        $this->allQueries = explode(';', $commentsRemoved);
    }

    protected function clean()
    {
        return $this->formatter->removeComments($this->sourceFile);
    }

    protected function formattedSource()
    {
        return $formattedSource;
    }
}