<?php
namespace Generator\ClassGenerator;

use Generator\ClassGenerator\ClassN\ClassController;

class ClassGenerator
{
        protected $controller;

        public function __construct($data = null)
        {
            $this->controller = new ClassController();
            if($data) {
                $this->compileClass($data);
            }
        }

        public function compileClass($data = null)
        {
            $data = ($data) ? : $this->data;

            $this->controller->makeClass($data);
        }

        public function getCompiledClass()
        {
            return $this->controller->getClass();
        }
    
}