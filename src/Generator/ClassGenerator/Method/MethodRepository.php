<?php
namespace Generator\ClassGenerator\Method;

use Generator\ClassGenerator\Contracts\IMethod as IMethod;
use Generator\ClassGenerator\Method\Method;

class MethodRepository implements IMethod
{
    protected $method;

    public function make($data = [])
    {
        $this->method = new Method($data);
    }

    public function getMethodToWrite()
    {
        return $this->method->generatedMethod;
    }

}