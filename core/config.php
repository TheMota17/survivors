<?php
	$config =
	[
		'db' => [
			'dbname' => 'survivors',
			'name'   => 'mysql',
			'pass'   => 'mysql'
		],
		'game' => [
			'update_time' => 5, //Sec
			'max' => [
				'x' => 470,
				'y' => 470,
				'weather' => 5,
				'temp' => 4
			]
		],
		'chat' => [
			'maxtime' => 3600 // Сообщения общего чата за последний час
		]
	];