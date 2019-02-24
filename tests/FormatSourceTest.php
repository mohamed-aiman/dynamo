<?php

use Generator\Migrations\FormatMySQLSource;
use PHPUnit\Framework\TestCase;

final class FormatSourceTest extends TestCase
{

    public function testGenerator()
    {
		$formatter =  new FormatMySQLSource();
		$formatter->generateMigrations();
    }
}