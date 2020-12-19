<?php
    require realpath('../db.php');
    require realpath('../funcs.php');

    Class Auth {

        public function __construct($pdo, $name_reg, $pass_reg, $mail_reg, $name_ent, $pass_ent) 
        {

            $this->pdo = $pdo;

            $this->name_reg = htmlspecialchars( trim( $name_reg ) );
            $this->pass_reg = htmlspecialchars( trim( $pass_reg ) );
            $this->mail_reg = htmlspecialchars( trim( $mail_reg ) );
            
            $this->name_ent = htmlspecialchars( trim( $name_ent ) );
            $this->pass_ent = htmlspecialchars( trim( $pass_ent ) );

        }
        
        public function mail_verif() {

            if (verifMail( $this->mail_reg )) {
                $find_mail = $this->pdo->fetch('SELECT `id` FROM `users` WHERE `mail` = ?', array( $this->mail_reg ));

                if ($find_mail) {
                    $this->message = 'Данный email уже занят!';
                }
            } else {
                $this->message = 'Неправильный формат email!';
            }
            
        }

        public function name_verif() {

            if (verifName( $this->name_reg )) {
                $find_name = $this->pdo->fetch('SELECT `id` FROM `users` WHERE `login` = ?', array( $this->name_reg ));

                if ( $find_name ) {
                    $this->message = 'Данный ник уже занят!';
                }
            } else {
                $this->message = 'Неправильный формат ника!';
            }

        }

        public function pass_verif() {

            if (!verifPass($this->pass_reg)) {
                $this->message = 'Неправильный формат пароля!';
            }

        }
        
        public function reg() {

            $newuser = $this->pdo->query('INSERT INTO users (login, pass, mail, date, lastvisit, ban, adm, live, hp, hung, thirst, fatigue, user_time, user_weather, user_temp, loc, loc_explored, costumize, craft_lvl, in_refuge) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            array($this->name_reg, password_hash($this->pass_reg, PASSWORD_DEFAULT), password_hash($this->mail_reg, PASSWORD_DEFAULT), time(), 0, 0, 0, 3, 100, 0, 0, 0, 36000, 1, 2, 1, 0, 0, 1, 0));
            $userid = $this->pdo->last();

            $refuge = $this->pdo->query('INSERT INTO refuge (hp, lvl, user_id) VALUES (?, ?, ?)', array(0, 0, $userid));
            $nadeto = $this->pdo->query('INSERT INTO nadeto (helm, arm, weap, hair, beard, cloth, pants, fwear, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)', array(0, 0, 0, 1, 1, 1, 1, 1, $userid));

            $food   = $this->pdo->query('INSERT INTO ivent (item, type, colvo, user_id) VALUES (?, ?, ?, ?)', array(13, 1, 5, $userid));
            $water  = $this->pdo->query('INSERT INTO ivent (item, type, colvo, user_id) VALUES (?, ?, ?, ?)', array(2, 1, 5, $userid));

            $_SESSION['user']  = $userid;
            
            $this->answer('page', '/costumize');
            
        }
        
        public function enter() {

            $find_user = $this->pdo->fetch('SELECT * FROM `users` WHERE `login` = ?', array($this->name_ent));

            if ($find_user && password_verify($this->pass_ent, $find_user['pass'])) {
                if ($find_user['ban'] > time()) {
                    $ban_time = date('d.m.Y H:i:s', $find_user['ban']);

                    $this->message = 'Вы заблокированы до - <div class=\'flex j-c mt5 mb5\'>'.$ban_time.'</div>';
                    $this->answer('mess', 0);
                } else {
                    $_SESSION['user'] = $find_user[ 'id' ];
                    $this->answer('page', '/game');
                }
            } else {
                $this->message = 'Нeверный ник или пароль!';
                $this->answer('mess', 0);
            }

        }

        public function answer($ans, $page) {

            switch( $ans ) {
                case 'page':
                    exit( json_encode( ['page' => $page, 'popup' => false] ) );
                break;
                case 'mess':
                    exit( json_encode( ['message' => $this->message, 'popup' => true] ) );
                break;
            }

        }

        public function main() {

            if (isset($_POST[ 'reg' ])) {
                $this->mail_verif();
                $this->pass_verif();
                $this->name_verif();

                if (empty( $this->message )) $this->reg();
                else $this->answer('mess', 0);
                
            } else if (isset($_POST['enter'])) {
                $this->enter();
            }

        }
    }

    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        if ($_SESSION['token'] == $_POST['token'] || $_POST['token'] == 0 || $_SESSION['token'] == 0) {
            $Auth = new Auth($pdo, $_POST['reg_name'], $_POST['reg_pass'], $_POST['reg_mail'], $_POST['enter_name'], $_POST['enter_pass']);
            $Auth->main();
        }
    } else { exit('Hi!'); }