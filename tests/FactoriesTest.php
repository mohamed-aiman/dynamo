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
        $written = file_put_contents(__DIR__ . '/../migrations_data.txt', print_r($compiled, true));
        dd($written);
  //       $factoriesGenerator = new FactoriesGenerator($compiled);
		// dd($compiled);

    }
}