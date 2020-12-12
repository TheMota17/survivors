<?php
    require 'sys.php';

    Class Panel {
        private $pdo;

        private $message;
                
        private $user;

        private $move;
        private $unMove;

        private $newuser_name;
        private $newuser_pass;
        private $newuser_mail;
        private $userinfo_id;
        private $ban_id;
        private $ban_time;
        private $data_tablename;
        private $data_cellname;
        private $data_idtype;
        private $data_id;
        private $data_changeon;

        public function __construct($pdo, $user, $move, $unMove)
        {
            
            $this->pdo = $pdo;

            $this->user = $user;

            $this->move   = htmlspecialchars( trim( $move ) );
            $this->unMove = htmlspecialchars( trim( $unMove) );

        }

        public function isAdmin() {

            if ($this->user['adm'] !== 1) exit();
            else return true;

        }

        public function name_verif() {

            if (verifName( $this->newuser_name )) {
                $find_name = $this->pdo->fetch('SELECT `id` FROM `users` WHERE `login` = ?', array( $this->newuser_name ));

                if ( $find_name ) {
                    $this->message = 'Данный ник уже занят!';
                    $this->answer('mess', 0);
                } else return true;
            } else {
                $this->message = 'Неправильный формат ника!';
                $this->answer('mess', 0);
            }

        }

        public function pass_verif() {

            if (!verifPass($this->newuser_pass)) {
                $this->message = 'Неправильный формат пароля!';
                $this->answer('mess', 0);
            } else return true;

        }

        public function mail_verif() {

            if (verifMail( $this->newuser_mail )) {
                $find_mail = $this->pdo->fetch('SELECT `id` FROM `users` WHERE `mail` = ?', array( $this->newuser_mail ));

                if ($find_mail) {
                    $this->message = 'Данный email уже занят!';
                    $this->asnwer('mess', 0);
                } else return true;
            } else {
                $this->message = 'Неправильный формат email!';
                $this->answer('mess', 0);
            }
            
        }

        public function newuser() {

            if (!empty($this->newuser_name) && !empty($this->newuser_pass) && !empty($this->newuser_mail)) return true;
            else {
                $this->message = 'Данные не правильно введены';
                $this->answer('mess', 0);
            }

        }

        public function create() {

            switch( $this->unMove ) {
                case 'newuser':
                    if ($this->newuser() && $this->name_verif() && $this->pass_verif() && $this->mail_verif()) {
                        $newuser = $this->pdo->query('INSERT INTO users (login, pass, mail, date, lastvisit, ban, adm, hp, hung, thirst, fatigue, user_time, user_weather, user_temp, loc, loc_explored, costumize) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                        array($this->newuser_name, $this->newuser_pass, $this->newuser_mail, time(), 0, 0, 0, 100, 0, 0, 0, 36000, 1, 2, 1, 0, 0));

                        $userid = $this->pdo->last();

                        $food   = $this->pdo->query('INSERT INTO ivent (item, type, colvo, user_id) VALUES (?, ?, ?, ?)', array(1, 1, 5, $userid));
                        $water  = $this->pdo->query('INSERT INTO ivent (item, type, colvo, user_id) VALUES (?, ?, ?, ?)', array(2, 1, 5, $userid));
                        $nadeto = $this->pdo->query('INSERT INTO nadeto (helm, arm, weap, hair, beard, cloth, pants, fwear, user_id)  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)', array(0, 0, 0, 1, 1, 1, 1, 1, $userid));
                            
                        $this->message = 'Игрок успешно добавлен!';
                        $this->answer('mess', 0);
                    }
                break;
            }

        }

        public function read() {

            switch( $this->unMove ) {
                case 'userinfo':
                    if ($this->userinfo_id) {
                        $userinfo = $this->pdo->fetch('SELECT * FROM `users` WHERE `id` = ?', array($this->userinfo_id));
                        if ($userinfo) {
                            $answer = [
                                'dom_data' => true,
                                'data' => [ 
                                    'userinfo' =>
                                    '
                                        <div class=\'flex j-s\'>Ник: '.$userinfo['login'].'</div>
                                        <div class=\'flex j-s\'>Дата рег: '.$userinfo['date'].'</div>
                                        <div class=\'flex j-s\'>Бан до: '.date('d.m.Y H:i:s', $userinfo['ban']).'</div>
                                        <div class=\'flex j-s\'>Админ: '.$userinfo['adm'].'</div>
                                        <div class=\'flex j-s\'>hp: '.$userinfo['hp'].'</div>
                                        <div class=\'flex j-s\'>Голод: '.$userinfo['hung'].'</div>
                                        <div class=\'flex j-s\'>Жажда: '.$userinfo['thirst'].'</div>
                                        <div class=\'flex j-s\'>Усталость: '.$userinfo['fatigue'].'</div>
                                        <div class=\'flex j-s\'>Внутри игр. время: '.$userinfo['user_time'].'</div>
                                        <div class=\'flex j-s\'>Погода: '.$userinfo['user_weather'].'</div>
                                        <div class=\'flex j-s\'>Температура: '.$userinfo['user_temp'].'</div>
                                        <div class=\'flex j-s\'>Локация: '.$userinfo['loc'].'</div>
                                        <div class=\'flex j-s\'>Процент исследования: '.$userinfo['loc_explored'].'</div>
                                        <div class=\'flex j-s\'>Игрок выбрал стиль перса: '.$userinfo['costumize'].'</div>
                                    '
                                ]
                            ];
                            $this->answer('dom', $answer);
                        } else {
                            $this->message = 'Игрок не найден';
                            $this->answer('mess', 0);
                        }
                    } else {
                        $this->message = 'Данные не правильно введены';
                        $this->answer('mess', 0);
                    }
                break;
            }

        }

        public function update() {

            switch( $this->unMove ) {
                case 'update':
                    
                    if ($this->data_tablename && $this->data_cellname && $this->data_idtype && $this->data_id && $this->data_changeon) {

                        switch( $this->data_idtype ) {
                            case 1:
                                
                            break;
                            case 2:

                            break;
                        }

                    }

                break;
            }

        }

        public function delete() {

            switch( $this->unMove ) {
                case 'ban':
                    
                    if ($this->ban_id && $this->ban_time) {
                        $ban = $this->pdo->query('UPDATE users SET `ban` = ? WHERE `id` = ?', array((time() + $this->ban_time), $this->ban_id));
                        if ($ban) {
                            $this->message = 'Игрок успешно забанен!';
                            $this->answer('mess', 0);
                        } else {
                            $this->message = 'Игрок не найден';
                            $this->answer('mess', 0);
                        }
                    } else {
                        $this->message = 'Данные не правильно введены';
                        $this->answer('mess', 0);
                    }

                break;
            }

        }

        public function answer($ans, $data) {

            switch( $ans ) {
                case 'done':
                    exit( json_encode( ['message' => 'done', 'popup' => false] ) );
                break;
                case 'mess':
                    exit( json_encode( ['message' => $this->message, 'popup' => true] ) );
                break;
                case 'exit':
                    exit( $this->message );
                break;
                case 'dom':
                    exit( json_encode($data) );
                break;
            }

        }

        public function main() {

            if ($this->isAdmin()) {
                switch( $this->move ) {
                    case 'create':
                        $this->newuser_name = htmlspecialchars( trim( $_POST['name'] ) );
                        $this->newuser_pass = htmlspecialchars( trim( $_POST['pass'] ) );
                        $this->newuser_mail = htmlspecialchars( trim( $_POST['mail'] ) );

                        $this->create();
                    break;
                    case 'read':
                        $this->userinfo_id = htmlspecialchars( trim( $_POST['id'] ) );

                        $this->read();
                    break;
                    case 'update':
                        $this->data_tablename = htmlspecialchars( trim( $_POST['tablename'] ) );
                        $this->data_cellname  = htmlspecialchars( trim( $_POST['cellname'] ) );
                        $this->data_idtype    = htmlspecialchars( trim( $_POST['idtype'] ) );
                        $this->data_id        = htmlspecialchars( trim( $_POST['id'] ) );
                        $this->data_changeon  = htmlspecialchars( trim( $_POST['changeon'] ) );

                        $this->update();
                    break;
                    case 'delete':
                        $this->ban_id   = htmlspecialchars( trim( $_POST['id'] ) );
                        $this->ban_time = htmlspecialchars( trim( $_POST['time'] ) );

                        $this->delete();
                    break;
                }
            }

        }
    }

    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        if ($_SESSION['token'] == $_POST['token']) {
            $Panel = new Panel($pdo, $Sys->getUser(), $_GET['move'], $_GET['unmove']);
            $Panel->main();
        }
    } else { exit('Location: /'); }