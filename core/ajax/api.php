<?php
	require realpath('../User.php');
    require realpath('../gamedata.php');

    Class Api {

    	public function __construct($pdo, $items, $locs, $rares, $pred, $crafts, $refuges, $user, $game)
    	{
            
            $this->pdo = $pdo;

            $this->items   = $items;
            $this->locs    = $locs;
            $this->rares   = $rares;
            $this->pred    = $pred;
            $this->crafts  = $crafts;
            $this->refuges = $refuges;
            
            $this->user  = $user;
            $this->game  = $game;

    	}

        public function answer($type) {

            switch($type) {
                case 'game':
                    exit(
                        json_encode([
                            'game' => $this->game,
                            'items' => $this->items,
                            'locs' => $this->locs
                        ])
                    );
                    break;
                case 'invent':
                    exit(
                        json_encode([
                            'nadeto' => $this->nadeto,
                            'invent' => $this->invent,
                            'user'   => ['login' => $this->user['login'], 'live' => $this->user['live']],
                            'game'   => $this->game,
                            'items'  => $this->items,
                            'rares'  => $this->rares
                        ])
                    );
                    break;
                case 'item':
                    exit(
                        json_encode([
                            'item'   => $this->item,
                            'items'  => $this->items,
                            'pred'   => $this->pred,
                            'rares'  => $this->rares,
                            'nadeto' => $this->nadeto,
                            'nElems' => [2 => 'helm', 3 => 'arm', 4 => 'weap']
                        ])
                    );
                    break;
                case 'craft':
                    exit(
                        json_encode([
                            'items'  => $this->items,
                            'crafts' => $this->crafts,
                            'rares'  => $this->rares,
                            'user'   => ['craft_lvl' => $this->user['craft_lvl']]
                        ])
                    );
                    break;
                case 'refuge':
                    exit(
                        json_encode([
                            'items'   => $this->items,
                            'rares'   => $this->rares,
                            'refuges' => $this->refuges,
                            'refuge'  => $this->refuge,
                            'user'    => ['in_refuge' => $this->user['in_refuge']]
                        ])
                    );
                    break;
            }

        }

    	public function main() {
            
            switch($_GET['page']) {
                case '404':
                    exit();
                break;
                case 'game':
                    $this->answer('game');
                    break;
                case 'invent':
                    $this->nadeto = $this->pdo->fetch('SELECT * FROM `nadeto` WHERE `user_id` = ?', array($this->user[ 'id' ]));
                    $this->invent = $this->pdo->fetchAll('SELECT * FROM `invent` WHERE `colvo` > 0 AND `user_id` = ?', array($this->user[ 'id' ]));

                    $this->answer('invent');
                    break;
                case 'item':
                    $this->id = htmlspecialchars( intval( $_POST['id'] ) );
                    if ($this->id) {
                        $this->item = $this->pdo->fetch('SELECT * FROM `invent` WHERE `id` = ? AND `colvo` > 0 AND `user_id` = ?', array($this->id, $this->user['id']));
                    } else $this->item = false;

                    $this->nadeto = $this->pdo->fetch('SELECT * FROM `nadeto` WHERE `user_id` = ?', array($this->user[ 'id' ]));

                    $this->answer('item');
                    break;
                case 'craft':
                    $this->answer('craft');
                    break;
                case 'refuge':
                    $this->refuge = $this->pdo->fetch('SELECT * FROM `refuge` WHERE `user_id` = ?', array($this->user['id']));
                    $this->slots  = $this->pdo->fetchAll('SELECT * FROM `slots` WHERE `item` > 0 AND `user_id` = ?', array($this->user['id']));
                    $this->tools  = array();
                    $this->prot   = array();

                    foreach($this->slots as $s) {
                        switch($s['type']) {
                            case 1:
                                array_push($this->tools, $s);
                            break;
                            case 2:
                                array_push($this->prot, $s);
                            break;
                        }
                    }

                    $this->answer('refuge');
                    break;
            }

    	}
        
    }

    if ($Utils::checkSession()) {
        $Api = new Api($Pdo, $game_items, $game_locs, $game_rares, $items_pred, $game_crafts, $game_refuges, $User->get_user(), $User->get_game());
        $Api->main();
    }