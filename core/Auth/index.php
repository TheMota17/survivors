<?php
    require realpath('../db.php');
    require realpath('../Utils.php');

    Class Auth {

        public function __construct($Pdo, $Utils)
        {

            $this->pdo   = $Pdo;
            $this->utils = $Utils;

        }

        public function name_verif() {

            if ($this->utils::verifName( $this->name )) {
                $find_name = $this->pdo->fetch('SELECT `id` FROM `users` WHERE `login` = ?', array( $this->name ));

                if ( $find_name ) {
                    $this->message = 'Данный ник уже занят!';
                }
            } else {
                $this->message = 'Неправильный формат ника!';
            }

        }

        public function pass_verif() {

            if (!$this->utils::verifPass($this->pass)) {
                $this->message = 'Неправильный формат пароля!';
            }

        }

        public function mail_verif() {

            if ($this->utils::verifMail( $this->mail )) {
                $find_mail = $this->pdo->fetch('SELECT `id` FROM `users` WHERE `mail` = ?', array( $this->mail ));

                if ($find_mail) {
                    $this->message = 'Данный email уже занят!';
                }
            } else {
                $this->message = 'Неправильный формат email!';
            }
            
        }

        public function authCode_verif() {

            if ($_SESSION['authCode'] != $this->authCode) {
                $this->message = 'Код введен не верно!';
            }

        }
        
        public function reg() {

            $game = json_encode(['x' => 50, 'y' => 50, 'time' => 36000, 'weather' => 1, 'hp' => 100, 'hung' => 0, 'thirst' => 0, 'fatigue' => 0, 'temp' => 1, 'weatherTime' => 0, 'hungTime' => 0, 'thirstTime' => 0, 'fatigueTime' => 0]);

            $newuser = $this->pdo->query('INSERT INTO users (login, pass, mail, date, lastvisit, ban, adm, live, costumize, craft_lvl, in_refuge, loc, loc_explored, game) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            array($this->name, password_hash($this->pass, PASSWORD_DEFAULT), password_hash($this->mail, PASSWORD_DEFAULT), time(), 0, 0, 0, 3, 0, 1, 0, 1, 0, $game));
            $userid = $this->pdo->last();

            $refuge = $this->pdo->query('INSERT INTO refuge (hp, lvl, user_id) VALUES (?, ?, ?)', array(0, 0, $userid));
            $nadeto = $this->pdo->query('INSERT INTO nadeto (helm, arm, weap, hair, beard, cloth, pants, fwear, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)', array(0, 0, 0, 1, 1, 1, 1, 1, $userid));

            $food   = $this->pdo->query('INSERT INTO invent (item, type, colvo, user_id) VALUES (?, ?, ?, ?)', array(13, 1, 5, $userid));
            $water  = $this->pdo->query('INSERT INTO invent (item, type, colvo, user_id) VALUES (?, ?, ?, ?)', array(2, 1, 5, $userid));

            $_SESSION['user']  = $userid;
            
            $this->answer('page', 'costumize');
            
        }
        
        public function enter() {

            $find_user = $this->pdo->fetch('SELECT * FROM `users` WHERE `login` = ?', array($this->name));

            if ($find_user && password_verify($this->pass, $find_user['pass'])) {
                if ($find_user['ban'] > time()) {
                    $ban_time = date('d.m.Y H:i:s', $find_user['ban']);

                    $this->message = 'Вы заблокированы до - '.$ban_time.'';
                    $this->answer('mess', 0);
                } else {
                    $_SESSION['user']  = $find_user[ 'id' ];
                    $this->answer('page', '/');
                }
            } else {
                $this->message = 'Нeверный ник или пароль!';
                $this->answer('mess', 0);
            }

        }

        public function answer($ans, $page) {

            switch( $ans ) {
                case 'page':
                    exit(json_encode( ['page' => $page, 'token' => $_SESSION['token']] ));
                break;
                case 'mess':
                    exit(json_encode( ['message' => $this->message, 'popup' => true] ));
                break;
            }

        }

        public function main() {

            switch($_GET['action']) {
                case 'reg':
                    $this->name      = htmlspecialchars( trim( $_POST['name'] ) );
                    $this->pass      = htmlspecialchars( trim( $_POST['pass'] ) );
                    $this->mail      = htmlspecialchars( trim( $_POST['mail'] ) );
                    $this->authCode  = htmlspecialchars( trim( $_POST['authCode'] ) );

                    $this->name_verif();
                    $this->pass_verif();
                    $this->mail_verif();
                    $this->authCode_verif();

                    if (empty( $this->message )) $this->reg();
                    else $this->answer('mess', 0);
                break;
                case 'enter':
                    $this->name = htmlspecialchars( trim( $_POST['name'] ) );
                    $this->pass = htmlspecialchars( trim( $_POST['pass'] ) );

                    $this->enter();
                break;
            }

        }
    }

    if ($Utils::checkToken()) {
        $Auth = new Auth($Pdo, $Utils);
        $Auth->main();
    }