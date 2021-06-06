<?php

    require '../Cfg.php';
    require '../GameData.php';
    require '../Utils.php';

    require '../SessStart.php';
    require '../DbConnect.php';
	require '../User.php';

    Class Api {
    	public function __construct($items, $locs, $rares, $crafts, $user)
    	{
            $this->items  = $items;
            $this->locs   = $locs;
            $this->rares  = $rares;
            $this->crafts = $crafts;

            $this->user = $user;
    	}

        public function sendApi()
        {
            $api = $this->getSendingApi($_GET['path']);

            switch($_GET['path'])
            {
                case '/404':
                case '/':
                    Messenger::sendMessage(array(), false, false);
                case '/invent':
                    Messenger::sendMessage([
                        'nadeto' => $api['nadeto'],
                        'invent' => $api['invent'],
                        'user'   => ['login' => $this->user['login']],

                        'items'  => $this->items,
                        'rares'  => $this->rares
                    ], false, false);
                    break;
                case '/item':
                    Messenger::sendMessage([
                        'item'   => $api['item'],
                        'nadeto' => $api['nadeto'],

                        'items'  => $this->items,
                        'rares'  => $this->rares
                    ], false, false);
                    break;
                case '/craft':
                    Messenger::sendMessage([
                        'user'   => ['craft_lvl' => $this->user['craft_lvl']],

                        'items'  => $this->items,
                        'crafts' => $this->crafts,
                        'rares'  => $this->rares
                    ], false, false);
                    break;
            }
        }

    	public function getSendingApi($path)
        {
            switch($path)
            {
                case '/404':
                    return false;
                case '/':
                    return false;
                case '/invent':
                    return [
                        'nadeto' => PDO2::fetch('SELECT * FROM nadeto WHERE user_id = ?', array($this->user[ 'id' ])),
                        'invent' => PDO2::fetchAll('SELECT * FROM invent WHERE colvo > 0 AND user_id = ?', array($this->user[ 'id' ]))
                    ];
                case '/item':
                    return [
                        'item'   => PDO2::fetch('SELECT * FROM invent WHERE id = ? AND colvo > 0 AND user_id = ?', array(intval($_POST['id']), $this->user['id'])),
                        'nadeto' => PDO2::fetch('SELECT * FROM nadeto WHERE user_id = ?', array($this->user['id']))
                    ];
            }
    	}
    }

    if (CheckClient::cookies())
    {
        $User = new User($_COOKIE['user'], $_COOKIE['hash']);

        if (!$User->isBanned())
        {
            $Api = new Api($game_items, $game_locs, $game_rares, $game_crafts, $User->getUser());
            $Api->sendApi();
        } else
            Messenger::sendMessage(['message' => 'Вы заблокированы до - '. date('d.m.Y H:i:s', $User->getBanTime())], true, '/auth');
    } else
        Messenger::sendMessage(array(), false, '/auth');