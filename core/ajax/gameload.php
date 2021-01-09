<?php
	require realpath('../sys.php');
    require realpath('../gamedata.php');

    Class GameLoad {

    	public function __construct($pdo, $weathers, $temps, $user, $action)
    	{
            
            $this->pdo = $pdo;

            $this->weathers = $weathers;
            $this->temps    = $temps;

            $this->user   = $user;
            $this->action = htmlspecialchars( trim( $action ) );
            
    	}

        public function update() {

            $this->pdo->query('UPDATE users SET `hp` = ?, `hung` = ?, `thirst` = ?, `fatigue` = ? WHERE `id` = ?', array(
                $this->hp,
                $this->hung,
                $this->thirst,
                $this->fatigue,
                $this->user['id']
            ));

            $this->pdo->query('UPDATE map SET `data` = ? WHERE `user_id` = ?', array(
                json_encode(
                    [
                        'x' => $this->x,
                        'y' => $this->y, 
                        's' => $this->s, 
                        'time' => $this->time, 
                        'weather' => $this->weather,
                        'weatherTime' => $this->weatherTime,
                        'temp' => $this->temp, 
                        'loc' => $this->loc, 
                        'loc_explored' => $this->loc_explored
                    ]
                ),
                $this->user['id']
            ));

            $this->answer('update');

        }

        public function load() {

            $this->map = $this->pdo->fetch('SELECT * FROM `map` WHERE `user_id` = ?', array($this->user['id']));
            $this->map = json_decode( $this->map['data'] );

            $this->answer('load');

        }

        public function answer($type) {

            switch($type) {
                case 'load':
                    exit(
                        json_encode(
                            [
                                'game' => $this->map,
                                'user' => ['hp' => $this->user['hp'], 'hung' => $this->user['hung'], 'thirst' => $this->user['thirst'], 'fatigue' => $this->user['fatigue']],
                                'sys'  => ['weathers' => $this->weathers, 'temps' => $this->temps]
                            ]
                        )
                    );
                break;
                case 'update':
                    exit();
                break;
            }

        }

    	public function main() {
            
            switch($this->action) {
                case 'load':
                    $this->load();
                break;
                case 'update':
                    $this->x            = intval( htmlspecialchars( $_POST['x'] ) );
                    $this->y            = intval( htmlspecialchars( $_POST['y'] ) );
                    $this->s            = intval( htmlspecialchars( $_POST['s'] ) );
                    $this->time         = intval( htmlspecialchars( $_POST['time'] ) );
                    $this->weather      = intval( htmlspecialchars( $_POST['weather'] ) );
                    $this->weatherTime  = intval( htmlspecialchars( $_POST['weatherTime'] ) );
                    $this->temp         = intval( htmlspecialchars( $_POST['temp'] ) );
                    $this->loc          = intval( htmlspecialchars( $_POST['loc'] ) );
                    $this->loc_explored = intval( htmlspecialchars( $_POST['loc_explored'] ) );
                    $this->hp           = intval( htmlspecialchars( $_POST['hp'] ) );
                    $this->hung         = intval( htmlspecialchars( $_POST['hung'] ) );
                    $this->thirst       = intval( htmlspecialchars( $_POST['thirst'] ) );
                    $this->fatigue      = intval( htmlspecialchars( $_POST['fatigue'] ) );

                    $this->update();
                break;
            }

    	}
        
    }

    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        if ($_SESSION['token'] == $_POST['token'] && $_POST['token'] && $_SESSION['token']) {
            $GameLoad = new GameLoad($pdo, $game_weathers, $game_temps, $Sys->get_user(), $_GET['action']);
            $GameLoad->main();
        }
    } else exit;