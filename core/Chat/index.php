<?php
	require realpath('../User.php');
    require realpath('../gamedata.php');

    class Chat {

    	public function __construct($Pdo, $Utils, $config)
    	{

    		$this->pdo    = $Pdo;
    		$this->utils  = $Utils;
    		$this->config = $config;

    	}

    	public function main()
        {

    	}
    }

    if ($Utils::checkSession() && $Utils::checkToken())
    {
        $Chat = new Chat($Pdo, $Utils, $config);
        $Chat->main();
    }