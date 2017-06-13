<?php

namespace Generator\Models;

use Generator\Contracts\MakeableInterface as Makeable;

class MakeModels implements Makeable
{
	protected $config;

	public function __construct($config = [])
	{
		$this->config = $config;
	}

	public function getContentsToWrite()
	{
		return null;
	}

	
}