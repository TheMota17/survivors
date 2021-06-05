<?php

	try
	{
	    if ($PDO = new PDO('mysql:host=localhost;dbname='.$config['db']['dbname'].';charset=utf8', ''.$config['db']['name'].'', ''.$config['db']['pass'].''))
	        PDO2::$db = $PDO;
	} catch(Exception $e)
	{
	    Messenger::sendMessage(['message' => 'Что то пошло не так'], true, false);
	}