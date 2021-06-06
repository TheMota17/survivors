<?php

    require '../Cfg.php';
    require '../Utils.php';

    require '../SessStart.php';
    require '../DbConnect.php';

    Class Auth {
        public function nameVerified($name)
        {
            if (preg_match('/^[a-zA-Z0-9_]{3,14}$/', $name))
            	return true;
            else Messenger::sendMessage(['message' => 'Ник введен не верно!'], true, false);
        }

        public function findUserByName($name)
        {
        	return PDO2::fetch('SELECT * FROM users WHERE login = ?', array($name));
        }

        public function nameNotOcuppied($name)
        {
            // Если имя в бд найдено
            if ($this->findUserByName($name))
            	Messenger::sendMessage(['message' => 'Ник уже занят!'], true, false);
            else
            	return true;
        }

        public function passVerified($pass)
        {
            if (preg_match('/^[\da-zA-Z0-9_]{3,}$/', $pass))
            	return true;
            else Messenger::sendMessage(['message' => 'Пароль введен не верно!'], true, false);
        }

        public function mailVerified($mail)
        {
            if (filter_var($mail, FILTER_VALIDATE_EMAIL))
            	return true;
            else
            	Messenger::sendMessage(['message' => 'Введите правильную почту!'], true, false);
        }

        public function mailNotOcuppied($mail)
        {
            // Если почта в бд найдена
            if (PDO2::fetch('SELECT id FROM users WHERE mail = ?', array($mail)))
            	Messenger::sendMessage(['message' => 'Почта уже занята!'], true, false);
            else return true;
        }

        public function authCodeVerified($authCode)
        {
            if ($_SESSION['authCode'] == $authCode)
            	return true;
            else
            	Messenger::sendMessage(['message' => 'Код введен не верно!'], true, false);
        }

        public function registrateUser($name, $pass, $mail)
        {
            PDO2::query('INSERT INTO users (login, pass, mail, sess_hash, date, lastvisit, ban, adm, craft_lvl, loc, x, y, hp, maxhp, hung, thirst, rad, speed) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',array(
                $name, password_hash($pass, PASSWORD_DEFAULT), $mail, 0, time(), 0, 0, 0, 1, 1, 20, 20, 100, 100, 0, 0, 0, 100
            ));

            $userId = PDO2::last();

            $nadeto = PDO2::query('INSERT INTO nadeto (type2, type3, type4, user_id) VALUES (?, ?, ?, ?)', array(0, 0, 0, $userId));
            $food   = PDO2::query('INSERT INTO invent (item, type, colvo, user_id) VALUES (?, ?, ?, ?)', array(13, 1, 5, $userId));
            $water  = PDO2::query('INSERT INTO invent (item, type, colvo, user_id) VALUES (?, ?, ?, ?)', array(2, 1, 5, $userId));

            $this->changeSessHash($sessHash = GenRandCodes::sessHash(), $userId);

            setcookie('user', $userId,   time() + 3600, '/');
            setcookie('hash', $sessHash, time() + 3600, '/');
        }

        public function changeSessHash($hash, $id)
        {
            PDO2::query('UPDATE users SET sess_hash = ? WHERE id = ?', array($hash, $id));
        }

        public function signin()
        {
            switch($_GET['action'])
            {
                case 'reg':
                    $name     = htmlspecialchars( trim( $_POST['name'] ) );
                    $pass     = htmlspecialchars( trim( $_POST['pass'] ) );
                    $mail     = htmlspecialchars( trim( $_POST['mail'] ) );
                    $authCode = htmlspecialchars( trim( $_POST['authCode'] ) );

                    if ($this->authCodeVerified($authCode) &&
                    	$this->nameVerified($name) &&
                    	$this->nameNotOcuppied($name) &&
                    	$this->passVerified($pass) &&
                    	$this->mailVerified($mail) &&
                    	$this->mailNotOcuppied($mail))
                    {
                    	$this->registrateUser($name, $pass, $mail);
                    	$this->locateUserToPage('/');
                    }
                break;
                case 'enter':
                    $name = htmlspecialchars( trim( $_POST['name'] ) );
                    $pass = htmlspecialchars( trim( $_POST['pass'] ) );

                    if ($user = $this->findUserByName($name))
                    {
                        if (!password_verify($pass, $user['pass']))
                        {
                        	Messenger::sendMessage(['message' => 'Неверный ник или пароль!'], true, false);
                        }

                        if ($user['ban'] > time())
                        {
                            $banTime = date('d.m.Y H:i:s', $user['ban']);
                            Messenger::sendMessage(['message' => 'Вы заблокированы до - '.$banTime.'!'], true, false);
                        } else
                        {
                            $this->changeSessHash($sessHash = GenRandCodes::sessHash(), $user['id']);

                            setcookie('user', $user['id'], time() + 3600, '/');
                            setcookie('hash', $sessHash,   time() + 3600, '/');

                            Messenger::sendMessage(array(), false, '/');
                        }
                    } else
                    {
                        Messenger::sendMessage(['message' => 'Неверный ник или пароль!'], true, false);
                    }
                break;
            }
        }
    }

    $Auth = new Auth;
    $Auth->signin();