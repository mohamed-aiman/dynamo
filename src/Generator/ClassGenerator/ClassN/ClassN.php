<?php
namespace Generator\ClassGenerator\ClassN;

class ClassN
{
    protected $name;
    protected $visibility;
    protected $type;
    protected $namespace;
    protected $uses = [];
    protected $extends = [];
    protected $properties = [];
    protected $methods = [];
    public $generatedClass;

    public function __construct($data = [])
    {
        $this->name = ($data['name']) ? : null;
        $this->type = ($data['type']) ? : null;
        $this->visibility = ($data['visibility']) ? : null;
        $this->namespace = ($data['namespace']) ? : null;
        $this->uses = ($data['uses']) ? : [];
        $this->extends = ($data['extends']) ? : null;
        $this->properties = ($data['properties']) ? : [];
        $this->methods = ($data['methods']) ? : [];
        $this->makeClass();
    }

    protected function makeClass()
    {
        $this->generatedClass = $this->openClass() . PHP_EOL . $this->addProperties() . $this->addMethods() . $this->closeClass();
    }

    protected function openClass()
    {
        $string = '<?php' . PHP_EOL;
        if ($this->namespace) {
            $string .= $this->namespace . ';' . PHP_EOL;
        }
        if($this->uses) {
            foreach ($this->uses as $uses) {
                $string .= 'use ' . $uses . ';' . PHP_EOL;
            }
        }
        $visibility = ($this->visibility) ? $this->visibility . ' ' : null;
        $extends = ($this->extends) ? ' extends ' . $this->extends . '' : null;
        return $string .= PHP_EOL . $visibility . 'class ' . $this->name  . $extends . PHP_EOL . '{' . PHP_EOL;
    }

    protected function addProperties()
    {
        return ($this->properties) ? implode(PHP_EOL, $this->properties) . PHP_EOL : null;
    }

    protected function addMethods()
    {
        return ($this->methods) ? implode(PHP_EOL, $this->methods) . PHP_EOL : null;
    }

    protected function closeClass()
    {
        return PHP_EOL . "}";

    }
}