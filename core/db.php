<?php
	session_start();

	require 'config.php';
	require 'pdo.php';
	
	$pdo = new PDO('mysql:host=localhost;dbname='.$config['db']['dbname'].';charset=utf8', ''.$config['db']['name'].'', ''.$config['db']['pass'].'');
	$pdo = new db( $pdo );

	if ($config['db']['offEmulate']) $pdo->offEmulate();