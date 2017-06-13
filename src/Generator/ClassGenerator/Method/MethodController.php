<?php
namespace Generator\ClassGenerator\Method;

use Generator\ClassGenerator\Method\MethodRepository;

class MethodController
{
    protected $methodRepository;

    // public function __construct(IMethod $methodRepository = null)
    public function __construct(MethodRepository $methodRepository = null)
    {
        $this->methodRepository = ($methodRepository) ? $methodRepository : new MethodRepository();
    }

    public function makeMethod($data)
    {
        $this->methodRepository->make($data);
    }

    public function getMethod()
    {
        echo $this->methodRepository->getMethodToWrite();
    }
}

// $x = new MethodController();
// $x->makeMethod([
        //     'visibility' => 'public',
        //     'type'       => null,
        //     'name'       => 'testMethod',
        //     'parameters' => [
        //         'param1'    => 'array',
        //         'param2'    => null,
        //         'param3'    => 'null'
        //     ],
        //     'body'      => [
        //         '           echo \"hello world\";',
        //         'return $string = null;'
        //     ]
        // ]);
// $x->getMethod();