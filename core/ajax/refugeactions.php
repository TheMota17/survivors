<?php
	require realpath('../sys.php');
    require realpath('../gamedata.php');

    Class RefugeActions {

    	public function __construct($pdo, $refuges, $user, $action)
    	{
            
            $this->pdo = $pdo;

            $this->refuges = $refuges;

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

            if ($this->refuges[ $this->user['refuge'] + 1 ]) {
                $all_items = count($this->refuges[ $this->user['refuge'] + 1 ]['craft_items']);
                $all_exist = 0;
                $items       = array();
                $items_colvo = array();

                // Проверка на соответсвие предметов
                foreach ($this->refuges[ $this->user['refuge'] + 1 ]['craft_items'] as $ci) {
                    $item = $this->pdo->fetch('SELECT * FROM `ivent` WHERE `item` = ? AND `type` = ? AND `colvo` >= ? AND `user_id` = ?', array($ci['item'], $ci['type'], $ci['colvo'], $this->user['id']));
                    if ($item) {
                        array_push($items, $item);
                        array_push($items_colvo, $ci['colvo']);
                        $all_exist += 1;
                    }
                }
                // Если общее кол-во нужных предметов совпадет с проверенными
                if ($all_items == $all_exist) {
                    for($i = 0; $i < count( $items ); $i++) {
                        // Убераем из инвентаря необходимые вещи для крафта
                        $this->pdo->query('UPDATE ivent SET `colvo` = ? WHERE `item` = ? AND `type` = ? AND `user_id` = ?', array(($items[$i]['colvo'] - $items_colvo[ $i ]), $items[$i]['item'], $items[$i]['type'], $this->user['id']));
                    }
                    
                    // Повышаем уровень убежища
                    $this->pdo->query('UPDATE users SET `refuge` = ? WHERE `id` = ?', array($this->user['refuge'] + 1, $this->user['id']));
                    $this->pdo->query('UPDATE refuge SET `hp` = ? WHERE `user_id` = ?', array($this->refuges[ $this->user['refuge'] + 1 ]['maxhp'], $this->user['id']));

                    $this->message = '<div class=\'flex j-c ai-c\'>Успешно!</div>';
                    $this->answer('messreload', 0);
                } else {
                    $this->message = '<div class=\'flex j-c ai-c\'>Недостаточно ресурсов!</div>';
                    $this->answer('mess', 0);
                }
            } else {
                $this->message = '<div class=\'flex j-c ai-c\'>Максимальный уровень!</div>';
                $this->answer('mess', 0);
            }

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
            $RefugeActions = new RefugeActions($pdo, $game_refuges, $Sys->get_user(), $_GET['action']);
            $RefugeActions->main();
        }
    } else { exit('Hi!'); }