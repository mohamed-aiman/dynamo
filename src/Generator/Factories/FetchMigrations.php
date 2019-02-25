<?php

namespace Generator\Factories;

class FetchMigrations
{
	protected $migrationsPath;

	protected $compiled = [];


	public function __construct($migrationsPath)
	{
		$this->migrationsPath = $migrationsPath;
	}

	public function getMigrations()
	{
		$this->compiled['migrations'] = glob("$this->migrationsPath/*");

		return $this;
	}

	public function fetTableData()
	{
		foreach ($this->compiled['migrations'] as $key => $migrationFile) {
			// $this->compiled['contents'][$key] = file_get_contents($migrationFile);
			$this->compiled['tables'][$key] = $this->extractTableData(file_get_contents($migrationFile));
		}

		return $this;
	}

	public function extractTableData($migrationFileContent)
	{
		$tableData = new MigrationsDataExtractor($migrationFileContent);

		return $tableData->extract();
	}

	public function getCompiled()
	{
		return $this->compiled;
	}
}