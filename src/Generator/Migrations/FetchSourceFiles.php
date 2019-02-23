<?php

namespace Generator\Migrations;

use Generator\Contracts\FetcherInterface;
use Generator\Helpers\Helper;
use Generator\Markers\Marker;

class FetchSourceFiles implements FetcherInterface
{
	use Helper;

	protected $sourceFile;
	protected $stubs;
	protected $fileContents;
	protected $formattedSource;


	public function __construct($sourceFile)
	{
		$this->sourceFile = $sourceFile['raw'];
		$this->stubs = require_once("Stubs/MigrationStub.php");
		$this->marker = new Marker($this->getFileContents());
		$this->fetch();
		$this->writeFormattedSourceToAFile($sourceFile['formatted']);
	}

	public function fetch()
	{
		$this->getDbName();
		$this->getTables();
	}

	protected function writeFormattedSourceToAFile($path)
	{
		file_put_contents($path, print_r($this->formattedSource, true));
	}

	public function getFormattedSource()
	{
		return $this->formattedSource;
	}

	public function getFileContents()
	{
		try {
			$this->fileContents = PHP_EOL . file_get_contents($this->sourceFile);
		} catch (Exception $e) {
			throw new Exception("Error reading source file: $this->sourceFile, " . $e->getMessage());			
		}
		return $this->fileContents;
	}

	public function getDbName()
	{
		if (!$dbName = $this->getDbNameByDbCreateStatement()) {
			$dbName = $this->getDbNameByTableStatement();
		}

		$this->formattedSource['db'] = $dbName;
	}

	protected function getDbNameByDbCreateStatement()
	{
		$start = $this->stubs['mysql_stubs']['db_name']['between']['start'];
		$end = $this->stubs['mysql_stubs']['db_name']['between']['end'];

		return $this->marker->between($start, $end);
	}

	protected function getDbNameByTableStatement()
	{
		$start = $this->stubs['mysql_stubs']['db_name']['between_table']['start'];
		$end = $this->stubs['mysql_stubs']['db_name']['between_table']['end'];

		return $this->marker->between($start, $end);
	}

	protected function getTables()
	{
		$this->getTableNames();
		$this->getTableContents();
		$this->getTablesProperties();
	}

	protected function getTableNames($dbName = null)
	{
		$dbName = ($dbName) ? : $this->formattedSource['db'];
		//prepare
		$original = $this->stubs['mysql_stubs']['table_names']['between']['start'];
		$new = str_replace('{{db_name}}', $dbName, $original);
		$this->setDynamicValue($new, $this->stubs['mysql_stubs']['table_names']['between']['start']);
		$start = $new;
		$end = $this->stubs['mysql_stubs']['table_names']['between']['end'];
		//get
		$this->formattedSource['table_names'] = $this->marker->betweenAll($start,$end);
	}

	protected function getTableContents($tableNames = [])
	{
		$tableNames = ($tableNames) ? : $this->formattedSource['table_names'];
		//prepare
		$prefix = $this->stubs['mysql_stubs']['table_names']['between']['start'];
		$end = $this->stubs['mysql_stubs']['statement_end'];
		foreach ($tableNames as $tableName) {
			$new = $prefix . $tableName . '`';
			$this->setDynamicValue($new, $this->stubs['mysql_stubs'][$tableName . 'contents']['between']['start']);
			//get
			$this->formattedSource[$tableName]['contents'] = $this->marker->between($new ,$end);
		}
	}

	protected function getTablesProperties($tableNames = [])
	{
		$tableNames = ($tableNames) ? : $this->formattedSource['table_names'];
		foreach ($tableNames as $tableName) {
			$this->getTableProperties($tableName);
		}

	}

	protected function getTableProperties($tableName)
	{
		$tableContent = $this->formattedSource[$tableName]['contents'];
		$commaSeperated = explode($this->stubs['mysql_stubs']['lines']['between']['end'], $tableContent);
		//get columns lines
		foreach ($commaSeperated as $line) {
				$this->getTablePropertiesLineByLine($tableName, $line);
		}
	}

	protected function getTablePropertiesLineByLine($tableName, $line)
	{
		$ends = $this->stubs['mysql_stubs']['stops'];
		$indexes = $this->stubs['mysql_stubs']['indexes'];
		$notNullable  = $this->stubs['mysql_stubs']['not_nullable'];
		$nullable  = $this->stubs['mysql_stubs']['nullable'];
		foreach ($ends as $end) {
			$spaceReplaced = $this->spaceReplaced($end);
			$whiteSpaceRemovedLine = $this->spaceReplaced($line);		

			if(strpos($whiteSpaceRemovedLine, $spaceReplaced)) {
				$autoIncrement = (strpos($whiteSpaceRemovedLine, 'AUTO_INCREMENT') !== false) ? true: false;
				$explode = explode('`', $line);
				$this->formattedSource[$tableName]['columns'][] = $explode[1];
				if(stripos($explode[2], $notNullable) !== false) {
					$fieldMeta = trim(explode($notNullable, $explode[2])[0]);
					$this->formattedSource[$tableName]['columns_meta'][$explode[1]] = $this->getTypeSizeOptions($fieldMeta, $autoIncrement, false);
					// $this->formattedSource[$tableName]['columns_meta'][$explode[1]] = $explode[2];	
				} else if(stripos($explode[2], $nullable) !== false) {
					$fieldMeta = trim(explode($nullable, $explode[2])[0]);
					$this->formattedSource[$tableName]['columns_meta'][$explode[1]] = $this->getTypeSizeOptions($fieldMeta, $autoIncrement);
				} else {
					throw new \Exception("Error: fetching from source: Check whether the field is set for nullable or not, it should be one of them else use this else part to declare that there is something called not declaring anything!!");
					
				}
				break;
			} else {
				if(strpos($line, $indexes['PRIMARY KEY']['between']['start']) > 0) {
					$this->formattedSource[$tableName]['indexes']['primary_keys'] = $this->getPrimaryKey($line);
					break;
				}
				else if(strpos($line, $indexes['UNIQUE INDEX']['between']['start']) > 0) {
					$uniqueDetails = $this->getUnique($line);
					$this->formattedSource[$tableName]['indexes']['unique'][] = $uniqueDetails;
					$this->addToTableColumnMeta($tableName, $uniqueDetails['column'], 'unique', true);
					break;
				} 
				else if(strpos($line, $indexes['INDEX']['between']['start']) > 0) {
					$this->formattedSource[$tableName]['indexes']['indexes'][] = $line;
					break;
				} 
				else if(strpos($line, $indexes['CONSTRAINT']['between']['start']) > 0) {
					$this->formattedSource[$tableName]['indexes']['constraints'][] = $this->getConstraints($line);
					break;
				}
				
			}
		}
	}

	protected function addToTableColumnMeta($tableName, $colmnName, $metaType, $metaValue)
	{
		$this->formattedSource[$tableName]['columns_meta'][$colmnName][$metaType] = $metaValue;
	}

	protected function getTypeSizeOptions($typeWithSizeOrOptions, $autoIncrement = false, $nullable = true)
	{
		$meta = [];
		//type either having size or options
		if(strpos($typeWithSizeOrOptions, "(") !== false) {
			$str = $this->marker->between("(", ")", $typeWithSizeOrOptions);
			//type with size
			if(is_numeric($str)) {
				$size = $str;
				$str = "($size)";
				$type = explode($str, $typeWithSizeOrOptions)[0];
				array_push($meta, ['type' => $type, 'size' => $size, 'nullable' => $nullable]);
			//type with options
			} else {
				$decimalTypes  = $this->stubs['mysql_stubs']['decimal_types'];
				$type = explode("(", $typeWithSizeOrOptions)[0];
				if(in_array($type, $decimalTypes)) {
					$size = $this->marker->between("(", ")", $typeWithSizeOrOptions);
					array_push($meta, ['type' => $type, 'size' => $size, 'nullable' => $nullable]);
					
				} else {
					$optionsRaw = $this->marker->between("(", ")", $typeWithSizeOrOptions);
					$options = array_map('trim', explode(',', $optionsRaw));				
					array_push($meta, ['type' => $type, 'options' => $options, 'nullable' => $nullable]);
				}
			}
		//just the type like INT, DATE
		} else {
			if($autoIncrement) {
				array_push($meta, ['type' => $typeWithSizeOrOptions, 'size' => 'default', 'nullable' => $nullable, 'auto_increment' => $autoIncrement]);
			} else {
				array_push($meta, ['type' => $typeWithSizeOrOptions, 'size' => 'default', 'nullable' => $nullable]);
			}
		}

		return array_shift($meta);
	}

	protected function getConstraints($line)
	{
		$constraint = [];

		$constraint = trim($this->marker->between('CONSTRAINT `', '`'.PHP_EOL, $line));
		$foreignKey = $this->multipleRespectively(trim($this->marker->between('FOREIGN KEY (`', '`)'.PHP_EOL, $line)));
		$halfBeforeTable = 'REFERENCES `'. $this->formattedSource['db'] .'`.`';
		$referenceTable = trim($this->marker->between($halfBeforeTable, '` (`', $line));
		$halfWithTable = $halfBeforeTable . $referenceTable . '` (`';
		$referenceColumn = $this->multipleRespectively(trim($this->marker->between($halfWithTable, '`)', $line)));
		$onDelete = (trim($this->marker->between('ON DELETE ', PHP_EOL, $line)) === 'NO ACTION') ? null : trim($this->marker->between('ON DELETE ', PHP_EOL, $line)) ;
		$onUpdate = (trim($this->marker->between('ON UPDATE ', PHP_EOL . ')', $line)) === 'NO ACTION') ? null : trim($this->marker->between('ON UPDATE ', PHP_EOL . ')', $line)) ;
		$constraint = [
			'constraint'  => $constraint, 
			'foreign_key' => $foreignKey, 
			'reference_table' => $referenceTable,
			'reference_column'=> $referenceColumn,
			'on_update' => $onUpdate,
			'on_delete' => $onDelete
		];
		return $constraint;
	}

	protected function multipleRespectively($dirtyForeignKeys)
	{
		return explode('`,`',str_replace(' ', '', $dirtyForeignKeys));
	}

	protected function getPrimaryKey($line)
	{
		$keysString = str_replace('`', '', $this->marker->between('PRIMARY KEY (', ')', $line));

		if(strpos($keysString, ',') !== false) {
			return $keys = array_map('trim', explode(',', $keysString));
		} else {
			return [0 => trim($keysString)];
		}
		throw new Exception("something wrong with primary key!! see near " . $line);
	}

	protected function getUnique($line)
	{
		$name = $this->marker->between('UNIQUE INDEX `','` (`', $line);
		$halfBeforeColumn = "UNIQUE INDEX `" . $name . "` (`";
		$column = $this->marker->between($halfBeforeColumn, '`', $line);
		$unique = [
			'name' => $name,
			'column' => $column
		];
		return $unique;
	}

}
