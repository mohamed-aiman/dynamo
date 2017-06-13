<?php
namespace Generator\Classes;

class ClassGenerator
{
	protected $config;
	protected $file;

	public function __construct($classParams = [])
	{
		$this->namespace = ($classParams["namespace"]) ? : null;
		$this->use = ($classParams["use"]) ? : [];
		$this->classN = ($classParams["class"]) ? : [];
		$this->methods = ($classParams["methods"]) ? : [];
		$this->config = require_once("ClassConfig.php");
		$this->file = "testing.php";
	}

	public function createClass()
	{
		$string = $this->mergeLines();
		try {
			$file = fopen($this->file, 'w');
			fwrite($file, $string);
		} catch (Exception $e) {
			throw new Exception("Error writing to file" . $e->getMessage());
			
		}
	}

	public function mergeLines()
	{
		$string = $this->config["common"]["open"]; 
		if($this->namespace) {
			$string .= $this->config["class"]["namespace"] . $this->namespace . PHP_EOL;
		}
		if($this->use) {
			foreach ($this->use as $classPath) {
				$string .= $this->config["class"]["use"] . $classPath . PHP_EOL;
			}
		}
		if($this->classN) {
			$string .= $this->config["class"]["class"];
			if(isset($this->classN['name'])) {
				$string .= $this->classN['name'];
			}
			if(isset($this->classN['extends'])) {
				$string .= $this->config["class"]["extends"] . $this->classN['extends'];
			}
			if(isset($this->classN['implements'])) {
				$string .= $this->config["class"]["implements"] . implode(',', $this->classN['implements']);
			}
			$string .= PHP_EOL . $this->config['class']['open'] . PHP_EOL;
			$string .= PHP_EOL . $this->config['class']['close'];
		}
		return $string;
	}
}