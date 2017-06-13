<?php
namespace Generator\ClassGenerator\ClassN;

use Generator\ClassGenerator\ClassN\ClassRepository;

class ClassController
{
    protected $classRepository;

    public function __construct(ClassRepository $classRepository = null)
    {
        $this->classRepository = ($classRepository) ? $classRepository : new classRepository();
    }

    public function makeClass($data)
    {
        $this->classRepository->make($data);
    }

    public function getClass()
    {
        return $this->classRepository->getClassToWrite();
    }
}

// $data = [
//     'name' => 'SomeClass',
//     'type' => null,
//     'visibility' => null,
//     'namespace' => 'App\Something\Some',
//     'uses' => [
//         'App\This\Some',
//         'App\That\Somee'
//     ],
//     'properties' => [
//         'protected $var1 = test;',
//         'public $var2 = test;',
//         'private $var1 = test;'
//     ],
//     'methods' => [
//                     [
//                         'visibility' => 'public',
//                         'type'       => null,
//                         'name'       => 'testMethod',
//                         'parameters' => [
//                             'param1'    => 'array',
//                             'param2'    => null,
//                             'param3'    => 'null'
//                         ],
//                         'body'      => [
//                             '   echo "hello world";',
//                             '   return $string = null;'
//                         ]
//                     ]
//     ]
// ];

// $x = new ClassController();
// $x->makeClass($data);
// $x->getClass();