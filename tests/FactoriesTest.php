<?php

use Generator\Factories\FetchMigrations;
use PHPUnit\Framework\TestCase;

final class FactoriesTest extends TestCase
{

    public function testGenerator()
    {
    	$migrationsFolder = __DIR__ . '/../migrations';
    	// dd(glob("$migrationsFolder/*"));
		$fetcher =  new FetchMigrations($migrationsFolder);
		$compiled = $fetcher->getMigrations()->fetTableData()->getCompiled();
		dd($compiled);

    }
}