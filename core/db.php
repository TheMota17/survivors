<?php
	require 'config.php';
	require 'pdo.php';
	
	try {
	    if ($pdo = new PDO('mysql:host=localhost;dbname='.$config['db']['dbname'].';charset=utf8', ''.$config['db']['name'].'', ''.$config['db']['pass'].'')) {

	        $pdo = new db( $pdo );
	        if ($config['db']['offEmulate']) $pdo->offEmulate();
	        session_start();

	    } else {
	        throw new Exception('Что то пошло не так');
	    }
	} catch(Exception $e) {
	    echo $e->getMessage();
	}