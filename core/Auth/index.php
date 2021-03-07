<?php
    require realpath('../db.php');
    require realpath('../Utils.php');

    Class Auth {

        public function __construct($Pdo, $Utils)
        {

            $this->pdo   = $Pdo;
            $this->utils = $Utils;

        }

        public function nameVerified($name)
        {
            if ($this->utils::verifName($name)) return true;
        }

        public function findUserByName($name)
        {
            // Если имя в бд найдено
            return $this->pdo->fetch('SELECT * FROM `users` WHERE `login` = ?', array($name));
        }

        public function passVerified($pass)
        {
            if ($this->utils::verifPass($pass)) return true;
        }

        public function mailVerified($mail)
        {
            if ($this->utils::verifMail( $mail )) return true;
        }

        public function mailOcuppied($mail)
        {
            // Если майл в бд найден
            if ($this->pdo->fetch('SELECT `id` FROM `users` WHERE `mail` = ?', array($mail))) return true;
        }

        public function authCodeVerified($authCode)
        {
            if ($_SESSION['authCode'] == $authCode) return true;
        }

        public function registrateUser($name, $pass, $mail)
        {
            $this->pdo->query('INSERT INTO users (login, pass, mail, date, lastvisit, ban, adm, costumize, craft_lvl, in_refuge, loc, loc_explored, x, y, hp, hung, thirst, fatigue, speed, time, weather, temp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',array(
                $name, password_hash($pass, PASSWORD_DEFAULT), $mail, time(), 0, 0, 0, 0, 1, 0, 1, 0, 20, 20, 100, 0, 0, 0, 1, 36000, 1, 1
            ));

            $userId = $this->pdo->last();

            $refuge = $this->pdo->query('INSERT INTO refuge (hp, lvl, user_id) VALUES (?, ?, ?)', array(0, 0, $userId));
            $nadeto = $this->pdo->query('INSERT INTO nadeto (helm, arm, weap, hair, beard, cloth, pants, fwear, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)', array(0, 0, 0, 1, 1, 1, 1, 1, $userId));

            $food   = $this->pdo->query('INSERT INTO invent (item, type, colvo, user_id) VALUES (?, ?, ?, ?, ?)', array(13, 1, 5, $userId));
            $water  = $this->pdo->query('INSERT INTO invent (item, type, colvo, user_id) VALUES (?, ?, ?, ?, ?)', array(2, 1, 5, $userId));

            $_SESSION['user'] = $userId;
        }

        public function locateUserToPage($page)
        {
            exit(json_encode(['page' => $page, 'token' => isset($_SESSION['token']) ]));
        }

        public function answer($type, $message)
        {
            switch($type)
            {
                case 'mess':
                    exit(json_encode( ['message' => $message, 'popup' => true] ));
                break;
            }
        }

        public function main()
        {
            switch($_GET['action'])
            {
                case 'reg':
                    $name      = htmlspecialchars( trim( $_POST['name'] ) );
                    $pass      = htmlspecialchars( trim( $_POST['pass'] ) );
                    $mail      = htmlspecialchars( trim( $_POST['mail'] ) );
                    $authCode  = htmlspecialchars( trim( $_POST['authCode'] ) );

                    if ($this->authCodeVerified($authCode))
                    {
                        if ($this->nameVerified($name))
                        {
                            if (!$this->findUserByName($name))
                            {
                                if ($this->passVerified($pass))
                                {
                                    if ($this->mailVerified($mail))
                                    {
                                        if (!$this->mailOcuppied($mail))
                                        {
                                            $this->registrateUser($name, $pass, $mail);
                                            $this->locateUserToPage('/costumize');
                                        } else
                                        {
                                            $this->answer('mess', 'Почта уже занята!');
                                        }
                                    } else
                                    {
                                        $this->answer('mess', 'Введите правильную почту!');
                                    }
                                } else
                                {
                                    $this->answer('mess', 'Пароль введен не верно!');
                                }
                            } else
                            {
                                $this->answer('mess', 'Ник уже занят!');
                            }
                        } else
                        {
                            $this->answer('mess', 'Ник введен не верно!');
                        }
                    } else
                    {
                        $this->answer('mess', 'Код введен не верно!');
                    }
                break;
                case 'enter':
                    $name = htmlspecialchars( trim( $_POST['name'] ) );
                    $pass = htmlspecialchars( trim( $_POST['pass'] ) );

                    $user = $this->findUserByName($name);

                    if ($user)
                    {
                        if (password_verify($pass, $user['pass']))
                        {
                            if ($user['ban'] > time())
                            {
                                $banTime = date('d.m.Y H:i:s', $user['ban']);
                                $this->answer('mess', 'Вы заблокированы до - '.$banTime.'!');
                            } else
                            {
                                $_SESSION['user'] = $user['id'];
                                $this->locateUserToPage('/');
                            }
                        } else
                        {
                            $this->answer('mess', 'Неверный ник или пароль!');
                        }
                    } else
                    {
                        $this->answer('mess', 'Неверный ник или пароль!');
                    }

                    $this->enter();
                break;
            }
        }
    }

    if ($Utils::checkToken())
    {
        $Auth = new Auth($Pdo, $Utils);
        $Auth->main();
    }