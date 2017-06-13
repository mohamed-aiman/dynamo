<?php
namespace Generator\Contracts;

interface GeneratableInterface
{
	public function generate();
	public function make();
	public function write();
}