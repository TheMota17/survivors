<?php
	session_start();

	require 'pdo.php';
	
	$pdo = new PDO('mysql:host=localhost;dbname=survive;charset=utf8', 'root', '89626545803');
	$pdo = new db( $pdo );
	$pdo->offEmulate();