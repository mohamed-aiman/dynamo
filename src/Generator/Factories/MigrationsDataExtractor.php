<?php

namespace Generator\Factories;

class MigrationsDataExtractor
{
	protected $content;

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