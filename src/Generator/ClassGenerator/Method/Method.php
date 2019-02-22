<?php
namespace Generator\ClassGenerator\Method;

use Generator\ClassGenerator\Helpers\HelperTrait;

class Method
{
    use HelperTrait;

    protected $visibility;//public private protected
    protected $type;//static or normal
    protected $name;
    protected $parameters = []; //array containing methods params => data type
    protected $body;
    public $generatedMethod; //the string generated
    public function __construct($data) 
    {
        $this->visibility = (isset($data['visibility'])) ? $data['visibility'] . ' ' : null;
        $this->type = (isset($data['type'])) ? $data['type'] . ' ' : null;
        $this->name = (isset($data['name'])) ? $data['name'] . '' : null;
        $this->parameters = (isset($data['parameters'])) ? $data['parameters'] : [];
        $this->body = (isset($data['body'])) ? $this->bodyToString($data['body']) : null; //string of body from body writer
        $this->generateMethod($data);
    }

    protected function generateMethod($data)
    {
        $this->generatedMethod = $this->visibility . $this->type . 'function ' . $this->name . $this->paramsToString() . $this->body;
    }


    protected function bodyToString($lines = [])
    {
        $strBody = "    {" . PHP_EOL;
        foreach ($lines as $line) {
            $strBody .= $line . PHP_EOL;
        }
        return $strBody . '    }' . PHP_EOL;
    }

    protected function paramsToString()
    {
        if (!$this->parameters) {
            return '()' . PHP_EOL;
        }
        $params = $this->valueKeyArrayToString($this->parameters);
        return "(" . $params .")" . PHP_EOL;
    }
}