<?php
namespace Generator\ClassGenerator\ClassN;

use Generator\ClassGenerator\ClassN\ClassN;
use Generator\ClassGenerator\Contracts\IClass as IClass;
use Generator\ClassGenerator\Method\MethodRepository;

class ClassRepository
{
    protected $classN;
    protected $methodRepository;

    public function __construct(MethodRepository $methodRepository = null)
    {
    	$this->methodRepository = ($methodRepository) ? $methodRepository : new MethodRepository();
    }

    public function make($data = [])
    {
    	$methodObjects;
    	if($data['methods']) {
    		foreach ($data['methods'] as $method) {
    			$this->methodRepository->make($method);
    			$methodObjects[] = $this->methodRepository->getMethodToWrite();
    		}
    	}
    	$data['methods'] = $methodObjects;
        $this->classN = new classN($data);
    }

    public function getClassToWrite()
    {
        return $this->classN->generatedClass;
    }

}