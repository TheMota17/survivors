<?php
    require realpath('../db.php');
	require realpath('../Utils.php');

    Class AuthTokens {

    	public function __construct($Utils)
    	{
            
            $this->utils = $Utils;

    	}

    	public function main() {
            
            switch($_GET['action']) {
            	case 'get':
                    $_SESSION['sessToken'] = $this->utils::sessToken();
                    $_SESSION['authCode']  = $this->utils::authCode();

	                exit(
	                    json_encode([
                            'sessToken' => $_SESSION['sessToken'],
                            'authCode'  => $_SESSION['authCode']
	                    ])
	                );
            		break;
            }

    	}
        
    }

    $AuthTokens = new AuthTokens($Utils);
    $AuthTokens->main();