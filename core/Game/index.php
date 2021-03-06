<?php
	require realpath('../User.php');
    require realpath('../gamedata.php');

    Class GameLoad {

    	public function __construct($Pdo, $config, $weathers, $temps, $locs, $items, $user, $game)
    	{

            $this->pdo    = $Pdo;
            $this->config = $config;

            $this->weathers = $weathers;
            $this->temps    = $temps;
            $this->locs     = $locs;
            $this->items    = $items;

            $this->user = $user;
            $this->game = $game;

    	}

        public function playersJoin()
        {
            if ($this->players)
            {
                $allPlayers = array_merge($this->players, $this->user);
            } else
            {
                $allPlayers = [$this->user];
            }
            $this->allPlayers = [];

            foreach($allPlayers as $pl)
            {
                $player = [
                    'loc' => $pl['loc'],
                    'loc_explored' => $pl['loc_explored'],
                    'x' => $pl['x'],
                    'y' => $pl['y'],
                    'hp' => $pl['hp'],
                    'hung' => $pl['hung'],
                    'thirst' => $pl['thirst'],
                    'fatigue' => $pl['fatigue'],
                    'speed' => $pl['speed'],
                    'time' => $pl['time'],
                    'weather' => $pl['weather'],
                    'temp' => $pl['temp']
                ];

                if ($pl['id'] == $this->user['id'])
                {
                    $player['you'] = true;
                }

                array_push($this->allPlayers, $player);
            }
        }

        public function answer($type)
        {
            switch($type)
            {
                case 'getData':
                    exit(
                        json_encode([
                            'players' => $this->allPlayers,
                            'sys'  => ['weathers' => $this->weathers, 'temps' => $this->temps, 'locs' => $this->locs, 'items' => $this->items]
                        ])
                    );
                break;
            }
        }

    	public function main()
        {
            switch($_GET['action'])
            {
                case 'getData':
                    $this->players = $this->pdo->fetchAll('SELECT * FROM `users` WHERE `id` != ? AND `lastvisit` > ? AND `loc` = ?', array($this->user['id'], (time() - 600), $this->user['loc']));

                    $this->playersJoin();

                    $this->answer('getData');
                    break;
            }
    	}

    }

    if ($Utils::checkSession() && $Utils::checkToken())
    {
        $GameLoad = new GameLoad($Pdo, $config, $game_weathers, $game_temps, $game_locs, $game_items, $User->getUser(), $User->getGame());
        $GameLoad->main();
    }