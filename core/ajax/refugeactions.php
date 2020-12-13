<?php
	require realpath('../sys.php');
    require realpath('../gamedata.php');

    Class RefugeActions {

    	public function __construct($pdo, $user, $action)
    	{
            
            $this->pdo = $pdo;

    		$this->user    = $user;
            $this->action  = htmlspecialchars( $action );
            
    	}

        public function enter() {

            if ($this->user['refuge'] > 0) {
                if ($this->user['in_refuge']) {
                    $this->pdo->query('UPDATE users SET `in_refuge` = ? WHERE `id` = ?', array(0, $this->user['id']));
                } else {
                    $this->pdo->query('UPDATE users SET `in_refuge` = ? WHERE `id` = ?', array(1, $this->user['id']));
                }

                $this->answer('reload', 0);
            }

        }

        public function up() {

            

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

            switch( $this->action ) {
                case 'enterrefuge':
                    $this->enter();
                    break;
                case 'uprefuge':
                    $this->up();
                    break;
            }

    	}
    }

    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        if ($_SESSION['token'] == $_POST['token'] || $_POST['token'] == 0 || $_SESSION['token'] == 0) {
            $RefugeActions = new RefugeActions($pdo, $Sys->get_user(), $_GET['action']);
            $RefugeActions->main();
        }
    } else { exit('Hi!'); }