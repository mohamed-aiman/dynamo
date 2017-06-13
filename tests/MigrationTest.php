<?php

use PHPUnit\Framework\TestCase;

use Generator\Generator;
use Generator\ClassGenerator\ClassN\ClassController;
use Generator\ClassGenerator\ClassN\ClassN;

final class MigrationTest extends TestCase
{

    public function testGenerator()
    {

		// $config = require_once("./tests/config.php");

		// echo Generator::Generate();
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



		$generator =  new Generator();
		$generator->generateMigrations();
    }
}