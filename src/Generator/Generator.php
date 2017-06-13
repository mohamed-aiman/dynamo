<?php

namespace Generator;

use Generator\ClassGenerator\ClassGenerator;
use Generator\Migrations\FetchSourceFiles;
use Generator\Migrations\MakeMigrations;
use Generator\Migrations\WriteMigrations;
use Generator\Models\GenerateModels;
use Symfony\Component\Console\Output\ConsoleOutput;

class Generator
{

	public function __construct($config = null)
	{
		if (isset($config)) {
			$this->config = $config;
        } else {
			$this->config = require_once("Config/app.php");
        }
		$this->classGenerator = new ClassGenerator();
		$this->ouputHandler = new ConsoleOutput();

	}

	public function generate()
	{
		$this->generateMigrations($this->config['migrations']);
		// $this->generateModels($configAll['models']);
	}

	public function generateMigrations($configMigrations = [])
	{
		$configMigrations = ($configMigrations) ? $configMigrations : $this->config['migrations'];

		$fetch = new FetchSourceFiles($configMigrations['source']);
		// print_r($fetch->formattedSource);exit();
		$make = new MakeMigrations($fetch, $configMigrations['make']);
		// print_r($make->contentsToWrite);exit();
		$write = new WriteMigrations($this->classGenerator, $make, $this->ouputHandler);
		// return new GenerateMigrations($configMigrations, $make, $write);
	}

	public function generateModels($configModels = [])
	{
		new GenerateModels($configModels);
	}

}