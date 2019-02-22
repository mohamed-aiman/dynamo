<?php

namespace Generator\Migrations;

use Generator\Contracts\FetcherInterface;
use Generator\Contracts\MakeableInterface;
use Generator\Migrations\FetchSourceFiles;

class LaravelMakeMigrations implements MakeableInterface
{
	protected $formattedSource;
	protected $classArray;
	protected $contentsToWrite;
	protected $fakeSecond = 10;
	protected $fakeMinute = 10;

	protected $defaultSizes = [
		'VARCHAR' => 225
	];

	protected $types = [
        'CHAR',
        'VARCHAR',
        'INT',
        'SMALLINT',
        'SMALLINT UNSIGNED',
        'TINYINT UNSIGNED',
        'TINYINT',
        'INT UNSIGNED',
        'MEDIUMINT UNSIGNED',
        'TIMESTAMP',
        'YEAR',
        'TEXT',
        'ENUM',
        'DECIMAL',
        'SET',
        'BLOB',
        'DATE',
        'DATETIME',
        'BIGINT'
    ];

    protected $typeMapper = [
        'VARCHAR' => 'string',
        'SMALLINT' => 'smallInteger',
        'SMALLINT UNSIGNED' => 'smallInteger',
        'BIGINT' => 'bigInteger',
        'TIMESTAMP' => 'dateTime',
        'DATE'	=> 'date',
        'DATETIME' => 'dateTime',
        'INT' => 'integer',
        'TINYINT' => 'tinyInteger',
        'TINYINT UNSIGNED' => 'tinyInteger',
        'INT UNSIGNED' => 'integer',
        'MEDIUMINT UNSIGNED' => 'mediumInteger',
        'TEXT' => 'text',
        'ENUM' => 'enum',
        'DECIMAL' => 'decimal',
        'SET' => 'enum',
        'YEAR' => 'smallInteger',
        'BLOB' => 'text',
        'CHAR' => 'char'
    ];

    protected $acceptedTypes = [
        'bigIncrements', // ->   Incrementing ID (primary key) using a "UNSIGNED BIG INTEGER" equivalent.
        'bigInteger', // ->   BIGINT equivalent for the database.
        'binary', // ->BLOB equivalent for the database.
        'boolean', // ->  BOOLEAN equivalent for the database.
        'char', // ->   CHAR equivalent with a length.
        'date', // ->DATE equivalent for the database.
        'dateTime', // ->DATETIME equivalent for the database.
        'decimal', // ->   DECIMAL equivalent with a precision and scale.
        'double', // ->   DOUBLE equivalent with precision, 15 digits in total and 8 after the decimal point.
        'enum', // ->   ENUM equivalent for the database.
        'float', // ->   FLOAT equivalent for the database.
        'increments', // ->  Incrementing ID (primary key) using a "UNSIGNED INTEGER" equivalent.
        'integer', // ->  INTEGER equivalent for the database.
        'ipAddress', // ->  IP address equivalent for the database.
        'json', // ->   JSON equivalent for the database.
        'jsonb', // ->  JSONB equivalent for the database.
        'longText', // ->   LONGTEXT equivalent for the database.
        'macAddress', // ->  MAC address equivalent for the database.
        'mediumInteger', // ->  MEDIUMINT equivalent for the database.
        'mediumText', // -> MEDIUMTEXT equivalent for the database.
        'morphs', // ->Adds INTEGER taggable_id and STRING taggable_type.
        'nullableTimestamps', // ->  Same as timestamps(), except allows NULLs.
        'rememberToken', // ->   Adds remember_token as VARCHAR(100) NULL.
        'smallInteger', // -> SMALLINT equivalent for the database.
        'softDeletes', // -> Adds deleted_at column for soft deletes.
        'string', // ->   VARCHAR equivalent column. // ->   VARCHAR equivalent with a length.
        'text', // ->   TEXT equivalent for the database.
        'time', // ->   TIME equivalent for the database.
        'tinyInteger', // ->TINYINT equivalent for the database.
        'timestamp', // -> TIMESTAMP equivalent for the database. // ->  Adds created_at and updated_at columns.
        'uuid', // ->UUID equivalent for the database.
    ];

    protected $incrementsMapper = [
    	'INT' => 'increments',
    	'BIGINT' => 'bigIncrements'
    ];

	public function __construct(FetcherInterface $fetch, $config = null)
	{
		$this->fetch = $fetch->getFormattedSource();
		$this->config = $config;
		$this->makeMigrations();
	}

	public function makeMigrations()
	{
		$config = $this->config;
		//clean the folder
		if($config['clean_folder']) {
			$files = glob($config['output_path'] . DIRECTORY_SEPARATOR .'*'); // get all file names
			foreach($files as $file){
			  if(is_file($file))
			    unlink($file);
			}
		}

		if($config['order']) {
			//ordered tables
			$tables = $config['order'];
			//add remaining
			if($config['include_remaining']) {
				$allTableFromFormattedSource = $this->fetch['table_names'];
				$diff = array_diff($allTableFromFormattedSource, $tables);
				$tables = array_merge($tables, $diff);
			}
		} else {
			$tables = $this->fetch['table_names'];
		}
		// print_r($config['output_path']);exit();
		foreach ($tables as $table) {
			$this->prepareMigrationFiles($table, $config['output_path']);
		}
	}

	protected function getPath($name, $path)
    {
        return $path.'/'.$this->getDatePrefix().'_create_'.$name.'_table.php';
    }

    protected function getFakeMinutes()
    {
    	if ($this->fakeMinute == 59) {
    		$this->fakeMinute = 11;
    	}

    	return $this->fakeMinute;
    }

    protected function getFakeSeconds()
    {
    	if ($this->fakeSecond == 59) {
    		$this->fakeSecond = 15;
    		$this->fakeMinute++;
    	}

    	return $this->fakeSecond++;
    }

    public function getFakeMinuteSeconds()
    {
    	return $this->getFakeMinutes() . $this->getFakeSeconds();
    }

    protected function getDatePrefix()
    {
        return date('Y_m_d_Hi') . $this->getFakeMinuteSeconds();
    }

	public function prepareMigrationFiles($tableName, $path)
	{
		$className = $this->makeTableCreateClass($tableName);
		$up = $this->createUpMethod($tableName);
		$drop = $this->createDropMethod($tableName);
		$fileName = $this->getPath($tableName, $path);

		$this->contentsToWrite[] = [
			"file_name" => $fileName,
			"name"	=> $className,
			"type"	=> null,
			"visibility"	=> null,
			"namespace" => "",
			"uses" => [
				"Illuminate\Database\Schema\Blueprint",
				"Illuminate\Database\Migrations\Migration"
			],
			'properties' => [],
		    'methods' => [
				$up,
				$drop
		    ]
		];
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
		// print_r($this->contentsToWrite);

		// print_r($this->contentsToWrite);

		// $this->makeTablesCreate();
	}

	public function getContentsToWrite()
	{
		return $this->contentsToWrite;
	}

	public function createUpMethod($tableName)
	{
		$body = $this->upMethodBody($tableName);
		$method = [
	        'visibility' => '	public',
	        'type'       => null,
	        'name'       => 'up',
	        'parameters' => [],
	        'body'      => $body
	    ];
	    return $method;
	}

	public function upMethodBody($tableName)
	{
		$lines[] = '        Schema::create(\'' . $tableName . '\', function(Blueprint $table){';
		$lines = array_merge($lines, $this->columns($tableName));
		if($this->config['timestamps_for_all']) {
		$lines = array_merge($lines, $this->timestamps());
		}
		$lines = array_merge($lines, $this->foreignKeys($tableName));
		$lines[] ='        });';
		return $lines;
	}

	protected function columns($tableName)
	{
		$tableSource = $this->fetch[$tableName];

		if(isset($tableSource['columns'])) {
			foreach ($tableSource['columns'] as $column) {
				if(in_array($column, $this->fetch[$tableName]['indexes']['primary_keys'])) {
					$lines[] = $this->preparePrimaryKeyColumn($column, $tableSource['columns_meta'][$column], $this->fetch[$tableName]['indexes']);
				} else {
					$lines[] = $this->prepareColumn($column, $tableSource['columns_meta'][$column]);
				}


			}
		} else {
			throw new \Exception("Error making Migrations: I dont think there should be a table without no columns!!! table: " . $tableName);
		}
		return $lines;
	}

	protected function preparePrimaryKeyColumn($columnName, $columnMeta, $indexData)
	{
		$line = '            $table->';

		$type = $columnMeta['type'];
		$autoIncrement = (isset($columnMeta['auto_increment']))  ? $columnMeta['auto_increment'] : null;
		$size = (isset($columnMeta['size']))  ? $columnMeta['size'] : null;
		$nullable = ($columnMeta['nullable']) ? '->nullable()' : null;
		$unsigned = '->unsigned()';

		if($autoIncrement) {
			return $line .= $this->incrementsMapper[$type] . "('" . $columnName . "');";
		} else {
			if($this->config['id_as_auto_increment']) {
				return $line .= $this->incrementsMapper[$type] . "('" . $columnName . "');";
			}
			return $line .= $this->typeMapper[$type] . "('" . $columnName . "')" . $unsigned . $nullable . ";";
		}
	}					

	protected function prepareColumn($columnName, $columnMeta)
	{
		$line = '            $table->';

		$type = $columnMeta['type'];
		$size = (isset($columnMeta['size']))  ? $columnMeta['size'] : null;
		$options = (isset($columnMeta['options']))  ?  implode(",", ($columnMeta['options'])) : null;
		$unique = (isset($columnMeta['unique'])) ? '->unique()' : null;
		$nullable = ($columnMeta['nullable']) ? '->nullable()' : null;

		if(isset($this->typeMapper[$type])) {
			if(isset($this->defaultSizes[$type]) && $this->defaultSizes[$type] == $size) {
				$size = null;
			}
			if($options) {
				$line .= $this->typeMapper[$type] . "('" . $columnName . "',[" . $options . "])" . $unique . $nullable . ";";
			} else if($size) {
				$size = ($size === 'default') ? null : ", " . $size;
				$line .= $this->typeMapper[$type] . "('" . $columnName . "'". $size . ")" . $unique . $nullable . ";";
			} else {
				$line .= $this->typeMapper[$type] . "('" . $columnName . "')" . $unique . $nullable . ";";
			}
		} else {
			throw new \Exception("Error making Migrations:  add the new complex data condition here for the type " . ((is_array($columnMeta)) ? implode(', ', $columnMeta) : $columnMeta));
		}
		return $line;
	}

	public function foreignKeys($tableName) 
	{
		$lines = [];
		$constraints = (isset($this->fetch[$tableName]['indexes']['constraints']) ? $this->fetch[$tableName]['indexes']['constraints'] : null);
		if($constraints) {
			foreach ($constraints as $foreignKey) {
				$lines[] = $this->prepareForeignKey($foreignKey);
			}
		}
		return $lines;
	}

	protected function prepareForeignKey($foreignKeyData)
	{
        $line = '            $table->foreign(';
        $line .= "'" . $foreignKeyData['foreign_key'] . "')->references(";
        $line .= "'" . $foreignKeyData['reference_column'] . "')->on(";
        $line .= "'" . $foreignKeyData['reference_table']  . "')";
        if($foreignKeyData['on_update']) {
        	$line .= PHP_EOL . "            ->onUpdate('" . $foreignKeyData['on_update'] . "')";
        }
        if($foreignKeyData['on_delete']) {
        	$line .= PHP_EOL . "            ->onUpdate('" . $foreignKeyData['on_update'] . "')";
        }
        $line .= ";";
        return $line;
	}

	protected function timestamps()
	{
		$line[] = '            $table->timestamps();' . PHP_EOL;
		return $line;
	}


	public function createDropMethod($tableName)
	{
		$body = $this->downMethodBody($tableName);
		$method = [
	        'visibility' => '	public',
	        'type'       => null,
	        'name'       => 'down',
	        'parameters' => [],
	        'body'      => $body
	    ];
	    return $method;
	}

	protected function downMethodBody($tableName)
	{
		$lines[] = "        Schema::dropIfExists('" . $tableName . "');";
		return $lines;
	}

	public function makeTableCreateClass($table)
	{
		$temp = ucwords($table, '_');
		$table = str_replace('_', '', $temp);
		return 'Create' . $table . 'Table';
	}

}
