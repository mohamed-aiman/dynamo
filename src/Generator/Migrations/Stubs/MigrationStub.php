<?php

return [
	"mysql_stubs" => [
			"db_name" => [
				"between" => [
					"start" => "CREATE SCHEMA IF NOT EXISTS `",
					"end"	=>  "`"
				],
				"between_table" => [
					"start" => "CREATE TABLE IF NOT EXISTS `",
					"end"	=>  "`"
				]
			],
			"table_names" => [
				"between" => [
					"start" => "CREATE TABLE IF NOT EXISTS `{{db_name}}`.`",
					"end"	=>  "`"
				],
				"between_not_exists" => [
					"start" => "CREATE TABLE IF NOT EXISTS `",
					"end"	=>  "`"
				],
				"between_simple" => [
					"start" => "CREATE TABLE `",
					"end"	=>  "`"
				],
			],
			"{{table_name}}" => [
				"contents" => [
					"between" => [
						"start" => "CREATE TABLE IF NOT EXISTS `{{db_name}}`.`{{table_name}}` (",
						"end"   => "{{statement_end}}"
					],
					"between_not_exists" => [
						"start" => "CREATE TABLE IF NOT EXISTS `{{table_name}}` (",
						"end"   => "{{statement_end}}"
					],
					"between_simple" => [
						"start" => "CREATE TABLE `{{table_name}}` (",
						"end"   => "{{statement_end}}"
					]
				]
			],
			"statement_end" => ';',
			"stops" => [
		        'NOT',
		        'NULL',
		        'NOT NULL',
		    ],
		    'not_nullable' => ' NOT NULL',
		    'nullable' => ' NULL',
		    'decimal_types' => [
		    	'DECIMAL'
		    ],
			"data_types" => [
		        'CHAR',
		        'VARCHAR',
		        'INT',
		        'SMALLINT',
		        'SMALLINT UNSIGNED',
		        'TINYINT UNSIGNED',
		        'TINYINT',
		        'INT UNSIGNED',
		        'MEDIUMINT UNSIGNED',
		        'TIMESTAMP',
		        'YEAR',
		        'TEXT',
		        'ENUM',
		        'DECIMAL',
		        'SET',
		        'BLOB',
		        'DATETIME'
		    ],
		    "lines" => [
		    	"between" => [
		    		"end" => "," . PHP_EOL
		    	]
		    ],
		    "indexes" => [
				"PRIMARY KEY" => [
					"between" => [
						"start" => "PRIMARY KEY",
						"end"	=> ")," . PHP_EOL
					]
				],
				"UNIQUE INDEX" => [
					"between" => [
						"start" => "UNIQUE INDEX",
						"end"	=> ")," . PHP_EOL
					]
				],
				"INDEX" => [
					"between" => [
						"start" => "INDEX",
						"end"	=> ")," . PHP_EOL
					]
				],
				"CONSTRAINT" => [
					"between" => [
						"start" => "CONSTRAINT",
						"end"	=> ")," . PHP_EOL
					]
				]

		    ]
	]
];