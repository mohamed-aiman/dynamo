<?php
namespace Generator\ClassGenerator;

use Generator\ClassGenerator\ClassN\ClassController;

class ClassGenerator
{
        protected $controller;

        public function __construct($data = null)
        {
            $this->controller = new ClassController();
            if ($data) $this->compileClass($data);
        }

        public function compileClass($data = null)
        {
            $this->controller->makeClass(($data) ? : $this->data);
        }

        public function getCompiledClass()
        {
            return $this->controller->getClass();
        }
    
}