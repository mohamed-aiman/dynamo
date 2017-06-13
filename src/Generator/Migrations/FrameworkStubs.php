<?php

return [
	"migrations" => [
		"namespace" => "",
		"use" => [
			"use Illuminate\Database\Schema\Blueprint;
			 use Illuminate\Database\Migrations\Migration;"
		],
		"class" => [
			"class_open" => "class Create{{ucfirst(table_name)}}Table extends Migration" . PHP_EOL . "{",
		],
		"methods" => [
			"up" => [
				"open" => "    public function up()" . PHP_EOL . "{",
		    	"method_up_body_open" => "        Schema::create('{{table_name}}', function(Blueprint $table){",
		    	"id_increments" => "$table->increments('id');",
		    	"column" => "$table->datetime('{{column_name}}')";
		    	"additional" => [
			    	"nullable" => "->nullable()"
		    	],
		    	"timestamps" => "$table->timestamps()",
		    	"close" => "        });" . PHP_EOL . "}" . PHP_EOL . "",
			],
			"down" => [
				"complete" => "public function down()" . PHP_EOL . "{" . PHP_EOL . "Schema::drop('{{table_name}}');" . PHP_EOL . "}"
			]
		],
	],
];