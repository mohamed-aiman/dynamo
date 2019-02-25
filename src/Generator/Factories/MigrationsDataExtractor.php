<?php

namespace Generator\Factories;

class MigrationsDataExtractor
{
	protected $content;


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
        'DOUBLE' => 'double',
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

	public function __construct($migrationFileContent)
	{
		$this->content = $migrationFileContent;
	}

	public function extract()
	{
		return [
			'table_name' => $this->getTableName(),
			'columns_data' => $this->getColumnsData(),
		];
	}

	public function getTableName($content = null)
	{
		$content = ($content) ? $content : $this->content;

		return get_string_between($this->format($content), "Schema::create('", "',function(Blueprint");
	}

	protected function format($content)
	{
		$content = str_replace(' ', '', $content); //remove all spaces
		return str_replace('"', "'", $content); //change all " to '
	}

	public function getColumnsData($content = null)
	{
		$content = ($content) ? $content : $this->content;

		$lines = get_string_between_all($this->format($content), '$table->', ";");

		// dd($lines);
		$cleaned = [];
		foreach ($lines as $key => $line) {
			$cleaned[$key]= $this->fromColumnLine($line);
		}
		// dd($cleaned);
		return $cleaned;

	}

	protected function fromColumnLine($line)
	{
		$data = [];

		$data['line'] 	 = $line; //complete line
		$data['type'] 	 = $this->itentifyColumnType($line); //column type increments,string,dateTime,timestamps,integer, foreign
		$data['name'] 	 = $this->itentifyColumnName($line); //name of the column
		$data['options'] = $this->itentifyColumnOptions($line); //size if int, enum values if enum, null if created_at,updated_at
		$data['extras']  = $this->itentifyColumnExtras($line);//extra options, unique, nullable, references, on
		return $data;
	}

	protected function itentifyColumnType($line)
	{
		return get_string_upto($line, '(');
	}

	protected function itentifyColumnName($line)
	{
		return ($name = get_string_between($line, "('", "'")) ? $name : null;
	}

	protected function itentifyColumnOptions($line)
	{
		return ($options = get_string_between($line, ",", ")")) ? $options : null;
	}

	protected function itentifyColumnExtras($line)
	{
		$methodNames = get_string_between_all($line, '->', "("); //references,on,onUpdate,nullable,unique

		$data = [];
		foreach ($methodNames as $key => $value) {
			$data[$value] = get_string_between($line, "->$value('", "'");
		}

		return $data;
	}
}