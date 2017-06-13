<?php
namespace Generator\ClassGenerator\Contracts;

interface IMethod {
    public function make($data = []);
    public function getMethodToWrite();
}