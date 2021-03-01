<?php
	require realpath('../User.php');
    require realpath('../gamedata.php');

    Class GameLoad {

    	public function __construct($pdo, $config, $weathers, $temps, $locs, $items, $user, $game)
    	{

            $this->pdo    = $pdo;
            $this->config = $config;

            $this->weathers = $weathers;
            $this->temps    = $temps;
            $this->locs     = $locs;
            $this->items    = $items;

            $this->user = $user;
            $this->game = $game;

    	}

        public function update()
        {
            /*
            $this->pdo->query('UPDATE `users` SET `game` = ? WHERE `id` = ?', array(
                json_encode(
                    [
                        'x' => $this->x,
                        'y' => $this->y,

                        'time' => $this->time,
                        'weather' => $this->weather,
                        'temp' => $this->temp,

                        'hp' => $this->hp,
                        'hung' => $this->hung,
                        'thirst' => $this->thirst,
                        'fatigue' => $this->fatigue,

                        'weatherTime' => $this->weatherTime,
                        'hungTime' => $this->hungTime,
                        'thirstTime' => $this->thirstTime,
                        'fatigueTime' => $this->fatigueTime
                    ]
                ),
                $this->user['id']
            ));
            */

            $_SESSION['game_updated_data'] = [
                'x' => $this->x,
                'y' => $this->y,
                'time' => $this->time,
                'weather' => $this->weather,
                'temp' => $this->temp,
                'hp' => $this->hp,
                'hung' => $this->hung,
                'thirst' => $this->thirst,
                'fatigue' => $this->fatigue,
                'weatherTime' => $this->weatherTime,
                'hungTime' => $this->hungTime,
                'thirstTime' => $this->thirstTime,
                'fatigueTime' => $this->fatigueTime
            ];

            $this->answer('update');
        }

        public function answer($type)
        {
            switch($type)
            {
                case 'load':
                    $_SESSION['game_updated_data'] = [
                        'x' => $this->game->x,
                        'y' => $this->game->y,
                        'time' => $this->game->time,
                        'weather' => $this->game->weather,
                        'temp' => $this->game->temp,
                        'hp' => $this->game->hp,
                        'hung' => $this->game->hung,
                        'thirst' => $this->game->thirst,
                        'fatigue' => $this->game->fatigue,
                        'weatherTime' => $this->game->weatherTime,
                        'hungTime' => $this->game->hungTime,
                        'thirstTime' => $this->game->thirstTime,
                        'fatigueTime' => $this->game->fatigueTime
                    ];

                    $this->game->loc = $this->user['loc'];
                    $this->game->loc_explored = $this->user['loc_explored'];

                    exit(
                        json_encode([
                            'game' => $this->game,
                            'sys'  => ['weathers' => $this->weathers, 'temps' => $this->temps, 'locs' => $this->locs, 'items' => $this->items]
                        ])
                    );
                break;
                case 'update':
                    $_SESSION['game_updated_time'] = time();

                    exit();
                break;
            }
        }

        public function check($upd_time)
        {

            // Проверка через сколько секунд пришел update
            if ($upd_time < $this->config['game']['update_time'])
                { exit('Time'); }

            // Проверка координат
            $deltaX = abs($this->x - $_SESSION['game_updated_data']['x']);
            $deltaY = abs($this->y - $_SESSION['game_updated_data']['y']);
            if ($deltaX > $this->config['game']['max']['x'] || $deltaY > $this->config['game']['max']['y'])
                { exit('xy'); }

            // Проверка времени игры
            if ($this->time > 86400 || $this->time < 0)
                { exit('GameTime'); }

            // Проверка измененности характеристик
            if ($this->hp > $_SESSION['game_updated_data']['hp'] || $this->hung < $_SESSION['game_updated_data']['hung'] || $this->thirst < $_SESSION['game_updated_data']['thirst'] || $this->fatigue < $_SESSION['game_updated_data']['fatigue'])
                { exit('haract'); }

            // Проверка характеристик
            if ($this->hp > 100 || $this->hp < 0 || $this->hung > 100 || $this->hung < 0 || $this->thirst > 100 || $this->thirst < 0 || $this->fatigue > 100 || $this->fatigue < 0)
                { exit('-1 haract'); }

            // Проверка погоды и температуры
            if ($this->weather < 0 || $this->weather > $this->config['game']['max']['weather'] || $this->temp < 0 || $this->temp > $this->config['game']['max']['weather'])
                { exit('wt'); }

            //
            if ($this->hpTime < 0 || $this->hungTime < 0 || $this->thirstTime < 0 || $this->fatigueTime < 0)
                { exit('htime'); }

        }

    	public function main()
        {
            switch($_GET['action'])
            {
                case 'load':
                    $_SESSION['game_updated_time'] = time();

                    $this->answer('load');
                break;
                case 'update':
                    $this->x            = intval( htmlspecialchars( $_POST['x'] ) );
                    $this->y            = intval( htmlspecialchars( $_POST['y'] ) );

                    $this->time         = intval( htmlspecialchars( $_POST['time'] ) );
                    $this->weather      = intval( htmlspecialchars( $_POST['weather'] ) );
                    $this->temp         = intval( htmlspecialchars( $_POST['temp'] ) );

                    $this->weatherTime  = intval( htmlspecialchars( $_POST['weatherTime'] ) );
                    $this->hp           = intval( htmlspecialchars( $_POST['hp'] ) );
                    $this->hung         = intval( htmlspecialchars( $_POST['hung'] ) );
                    $this->hungTime     = intval( htmlspecialchars( $_POST['hungTime'] ) );
                    $this->thirst       = intval( htmlspecialchars( $_POST['thirst'] ) );
                    $this->thirstTime   = intval( htmlspecialchars( $_POST['thirstTime'] ) );
                    $this->fatigue      = intval( htmlspecialchars( $_POST['fatigue'] ) );
                    $this->fatigueTime  = intval( htmlspecialchars( $_POST['fatigueTime'] ) );

                    $this->check( (time() - $_SESSION['game_updated_time']) );

                    $this->update();
                break;
            }
    	}

    }

    if ($Utils::checkSession() && $Utils::checkToken())
    {
        $GameLoad = new GameLoad($Pdo, $config, $game_weathers, $game_temps, $game_locs, $game_items, $User->getUser(), $User->getGame());
        $GameLoad->main();
    }