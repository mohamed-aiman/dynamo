<?php

namespace Generator\Models;

use Generator\Core\Generate;

class GenerateModels extends Generate
{
	public function make()
	{
		$make= new MakeModels($this->config['make']);
		$this->contentsToWrite = $make->getContentsToWrite();
		return $this->contentsToWrite;
	}

	public function write()
	{
		$writer = new WriteModels($this->contentsToWrite);
		$writer->write();
	}

}