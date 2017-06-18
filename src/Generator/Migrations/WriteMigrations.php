<?php

namespace Generator\Migrations;

use Generator\ClassGenerator\ClassGenerator;
use Generator\Contracts\MakeableInterface as Makeable;
use Generator\Contracts\WritableInterface as Writable;
use Symfony\Component\Console\Output\ConsoleOutputInterface;

class WriteMigrations implements Writable
{
	public function __construct(ClassGenerator $classGenerator, Makeable $prepared, ConsoleOutputInterface $outputHandler)
	{
		$this->classGenerator = $classGenerator;
		$this->prepared = $prepared;
		$this->ouputHandler = $outputHandler;
		$this->write();
	}

	public function write()
	{
		$contents = $this->prepared->getContentsToWrite();
		foreach ($contents as $migration) {
			$this->classGenerator->compileClass($migration);
			$data = $this->classGenerator->getCompiledClass();
			$file = $migration['file_name'];
			$this->createDirectory($file);
			$handle = fopen($file, 'w+');
			try {
				if(fwrite($handle, $data)) {
					$this->ouputHandler->writeln("<info>$file created.</info>");
				}

			} catch (Exception $e) {
				$this->ouputHandler->writeln("unable to write!! file: $file details: " . $e->getMessage());
			}
				
		}
	}

	protected function createDirectory($fileName)
	{
		$directory = dirname($fileName);
		if (!is_dir($directory)) {
			try {
			    mkdir($directory);
			} catch (Exception $e) {
				$this->ouputHandler->writeln("unable to create folder for: $file details: " . $e->getMessage());
			}
		}
	}
}