<?php

return [
	'migrations' => [
	     //path to the source erd sql export file
		'source' => [
			'raw' => 'mysql.sql',
			'formatted' => 'formatted_source.txt'
			],
		//make: this array is loaded to the MakeMigraions as making migrations configs
		//order: tells the order of making migrations
		//id_as_auto_increment:  true means if id is set in a table it would be set as increments()
		//or bigIncrements() depending on the field type declared.
		//timestamps_for_all: adds timestamps()->useCurrent() to all migrations even if its not declared in dump
		//output_path: to where all the migrations to be created
		//clean_folder: removes all the previous file in the path, if any
		'make' => [
			'order' => [
				// 'users',
	            'items_features',
	            // 'listings',
	            // 'sellers',
	            // 'categories',
	            // 'images',
	            // 'scales',
	            // 'features',
	            // 'units',
	            // 'items'
			],
			'id_as_auto_increment' => true,
			'timestamps_for_all' => true,
			'include_remaining' => true,
			'output_path' => 'migrations',
			'clean_folder' => true
		],
	],
	'models' => [
		//where the array or the formatted source created by fetching source is to be placed
		//the file produced by migration fetching from source, change if you have moved it
		'formatted_source' => 'formatted_source.txt', 
		//config to be used by makeModels
		'make' => [
			//mapper maps the tables to its models. if its a pivot remove the model key from the related table mapper
		    //if the model key is null, default laravel pluralization would be used
		    //relationships: first the model make will read the migrations formatted_source and identify whether there is a relationships
		    //if it finds it will look into the relationships array to determine what kind of relation is it
			'mapper' => [
				"users" => [
					"model" => "", 
					"relationships" => []],
	            "items_features" => [
	            	"relationships" => []],
	            "listings" => [
	            	"model" => "", 
	            	"relationships" => []],
	            "sellers" => [
	            	"model" => "", 
	            	"relationships" => []],
	            "categories" => [
	            	"model" => "", 
	            	"relationships" => []],
	            "images" => [
	            	"model" => "", 
	            	"relationships" => []],
	            "scales" => [
	            	"model" => "", 
	            	"relationships" => []],
	            "features" => [
	            	"model" => "", 
	            	"relationships" => []],
	            "units" => [
	            	"model" => "", 
	            	"relationships" => []],
	            "items" => [
	            	"model" => "",
 					"relationships" => []],			
 			],
		]
	]
];