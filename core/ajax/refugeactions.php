<?php
	require realpath('../sys.php');
    require realpath('../gamedata.php');

    Class RefugeАctions {

    	public function __construct($pdo, $user, $action)
    	{
            
            $this->pdo = $pdo;

    		$this->user    = $user;
            $this->action  = htmlspecialchars( $action );
            
    	}

        public function answer($ans, $page) {

            switch( $ans ) {
                case 'page':
                    exit( json_encode( ['page' => $page] ) );
                    break;
                case 'mess':
                    exit( json_encode( ['message' => $this->message, 'popup' => true] ) );
                    break;
                case 'reload':
                    exit( json_encode( ['reload' => true] ) );
                    break;
                case 'messreload':
                    exit( json_encode( ['reload' => true, 'message' => $this->message, 'popup' => true] ) );
                    break;
            }

        }

    	public function main() {
            
            $this->from = htmlspecialchars( trim( $_POST['from'] ) );

            switch( $this->action ) {
                case 'enter':

                    break;
                case 'up':

                    break;
            }

    	}
    }

    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        if ($_SESSION['token'] == $_POST['token'] || $_POST['token'] == 0 || $_SESSION['token'] == 0) {
            $GameActions = new RefugeActions($pdo, $Sys->get_user(), $_GET['action']);
            $GameActions->main();
        }
    } else { exit('Hi!'); }