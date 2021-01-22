<?php
	require realpath('../sys.php');
    require realpath('../gamedata.php');

    Class Api {

    	public function __construct($pdo)
    	{
            
            $this->pdo = $pdo;
            
    	}

        public function answer($type) {



        }

    	public function main() {
            
            switch($_GET['page']) {
                case 'game':


                    $this->answer('game');
                break;
            }

    	}
        
    }

    if ($_SESSION['user'] && $_SESSION['token'] == $_POST['token'] && $_POST['token'] && $_SESSION['token']) {
        $Api = new Api($pdo);
        $Api->main();
    } else {
        exit( json_encode( ['page' => '/auth'] ) );
    }