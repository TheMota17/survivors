<?php
	require realpath('../User.php');
    require realpath('../gamedata.php');

    Class GameActions {

    	public function __construct($Pdo, $items, $locs, $crafts, $weathers, $temps, $refuges, $user, $game)
    	{

            $this->pdo = $Pdo;

            $this->items        = $items;
            $this->locs         = $locs;
            $this->crafts       = $crafts;
            $this->weathers     = $weathers;
            $this->temps        = $temps;
            $this->refuges      = $refuges;

    		$this->user = $user;
            $this->game = $game;

    	}

        public function srchItemInInvent($idItem)
        {
            return $this->pdo->fetch('SELECT * FROM `invent` WHERE `id` = ? AND `colvo` > 0 AND `user_id` = ?', array($idItem, $this->user['id']));
        }

        public function srchItemInInventWithoutId($type, $item)
        {
            return $this->pdo->fetch('SELECT * FROM `invent` WHERE `type` = ? AND `item` = ? AND `user_id` = ?', array(
                $type, $item, $this->user['id']
            ));
        }


        public function getItemEffect($type, $item, $eff)
        {
            return $this->items[ $type ][ $item ]['eff'][ $eff ];
        }

        public function itemSubstr($id, $lastColvo, $colvo)
        {
            $this->pdo->query('UPDATE `invent` SET `colvo` = ? WHERE `id` = ?', array(($lastColvo - $colvo), $id));
        }

        public function itemAddMore($id, $lastColvo, $colvo)
        {
            $this->pdo->query('UPDATE `invent` SET `colvo` = ? WHERE `id` = ?', array(($lastColvo + $colvo), $id));
        }

        public function hpCalculate($userHp, $effHp)
        {
            if (($userHp + $effHp) > 100) return 100;
            else return ($userHp + $effHp);
        }

        public function haractCalculate($userHar, $eff)
        {
            if (($userHar - $eff) <= 0) return 0;
            else return ($userHar - $eff);
        }

        public function eatUser($hp, $hung)
        {
            $this->pdo->query('UPDATE `users` SET `hp` = ?, `hung` = ? WHERE `id` = ?', array($hp, $hung, $this->user['id']));
        }

        public function drinkUser($hp, $thirst)
        {
            $this->pdo->query('UPDATE `users` SET `hp` = ?, `thirst` = ? WHERE `id` = ?', array($hp, $thirst, $this->user['id']));
        }

        public function toInventOrReload($itemColvo)
        {
            if ($itemColvo == 1)
            {
                $this->locateUserToPage('/invent');
            } else
            {
                $this->answer('reload', 0);
            }
        }

        public function eat()
        {
            if ($item = $this->srchItemInInvent($this->idItem))
            {
                if ($this->game['hung'] < $this->getItemEffect($item['type'], $item['item'], 'hung'))
                {
                    $this->answer('mess', 'Вы не голодны');
                } else
                {
                    $hp   = $this->hpCalculate($this->game['hp'], $this->getItemEffect($item['type'], $item['item'], 'hp'));
                    $hung = $this->haractCalculate($this->game['hung'], $this->getItemEffect($item['type'], $item['item'], 'hung'));

                    $this->eatUser($hp, $hung);
                    $this->itemSubstr($item['id'], $item['colvo'], 1);
                    $this->toInventOrReload($item['colvo']);
                }
            }
        }

        public function drink()
        {
            if ($item = $this->srchItemInInvent($this->idItem))
            {
                if ($this->game['thirst'] < $this->getItemEffect($item['type'], $item['item'], 'thirst'))
                {
                    $this->answer('mess', 'Вы не хотите пить');
                } else
                {
                    $hp     = $this->hpCalculate($this->game['hp'], $this->getItemEffect($item['type'], $item['item'], 'hp'));
                    $thirst = $this->haractCalculate($this->game['thirst'], $this->getItemEffect($item['type'], $item['item'], 'thirst'));

                    $this->drinkUser($hp, $thirst);
                    $this->itemSubstr($item['id'], $item['colvo'], 1
                );
                    $this->toInventOrReload($item['colvo']);
                }
            }
        }

        public function getNadItems()
        {
            return $this->pdo->fetch('SELECT * FROM `nadeto` WHERE `user_id` = ?', array($this->user['id']));
        }

        public function getItemTypeName($itemType)
        {
            return $this->itemTyps[ $itemType ];
        }

        public function createItem($item, $type, $colvo)
        {
            $this->pdo->query('INSERT INTO invent (item, type, colvo, user_id) VALUES (?, ?, ?, ?)', array(
                $item, $type, $colvo, $this->user['id']
            ));
        }

        public function nadetItem($type, $item)
        {
            $this->pdo->query('UPDATE `nadeto` SET `'.$type.'` = ? WHERE `user_id` = ?', array($item, $this->user['id']));
        }

        public function nadet()
        {
            $inventItem = $this->srchItemInInvent($this->idItem); // Надеваемый предмет из инвентаря
            $nadetoItems = $this->getNadItems(); // Все надетые предметы игрока

            if ($inventItem['item'] == $nadetoItems[ $this->getItemTypeName( $inventItem['type'] ) ])
            {
                $this->answer('mess', 'На вас уже надет данный предмет');
            } else
            {
                if ($nadetoItems[ $this->getItemTypeName($inventItem['type']) ] > 0) // Было ли надето на игрока вообще что то
                {
                    $putonItemInInvent = $this->srchItemInInventWithoutId($inventItem['type'], $nadetoItems[ $this->getItemTypeName($inventItem['type']) ]); // Ищем в инвентаре снимаемый предмет с игрока

                    if ($putonItemInInvent)
                    {
                        $this->itemAddMore($putonItemInInvent['id'], $putonItemInInvent['colvo'], 1); // Если предмет найден, то просто увеличиваем на единицу
                    } else
                    {
                        $this->createItem($nadetoItems[ $this->getItemTypeName($inventItem['type']) ], $inventItem['type'], 1, 0, $this->user['id']); // Если нет, то создаем новую запись для предмета
                    }
                }

                $this->nadetItem($this->getItemTypeName($inventItem['type']), $inventItem['item']); // Надеваем предмет на игрока
                $this->itemSubstr($inventItem['id'], $inventItem['colvo'], 1); // Надеваемый предмет вычитаем из инвентаря
            }

            $this->toInventOrReload($inventItem['colvo']);
        }

        public function srchItemInCrafts($id)
        {
            if ($this->crafts[ $id ]['item']) return true;
        }

        public function getUserTools()
        {
            return $this->pdo->fetchAll('SELECT * FROM `slots` WHERE `item` > 0 AND `type` = 1 AND `user_id` = ?', array($this->user['id']));
        }

        public function userCraftToolsExist($tools, $userTools)
        {
            $toolsColvo = count($tools);
            $toolsExist = 0;

            foreach($userTools as $tool)
            {
                for($i = 0; $i < $toolsColvo; $i++)
                {
                    if ($tool['item'] == $tools[ $i ]['item'])
                    {
                        $toolsExist += 1;
                    }
                }
            }

            if ($toolsExits >= $toolsColvo) return true;
        }

        public function userItemsExist($items, $colvo)
        {
            $itemsColvo = count($items);
            $itemsExist = 0;

            foreach($items as $item)
            {
                if ($itemInInvent = $this->srchItemInInventWithoutId($item['type'], $item['item']))
                {
                    if ($itemInInvent['colvo'] >= ($item['colvo'] * $colvo))
                    {
                        $itemsExist += 1;
                    }
                }
            }

            if ($itemsExist >= $itemsColvo) return true;
        }

        public function getAllItemsFromInvent()
        {
            return $this->pdo->fetchAll('SELECT * FROM `invent` WHERE `colvo` > 0 AND `user_id` = ?', array($this->user['id']));
        }

        public function craft()
        {
            if ($this->srchItemInCrafts($this->id)) // Если в массиве с крафтами есть такой предмет
            {
                if ($this->crafts[ $this->id ]['craft_lvl'] <= $this->user['craft_lvl']) // Если уровень крафта соответсвует уровню крафта игрока
                {
                    if (isset($this->crafts[ $this->id ]['tools'])) // Если для крафта необходимы инструменты убежища
                    {
                        if ( !$this->userCraftToolsExist($this->crafts[ $this->id ]['tools'], $this->getUserTools()) )
                        {
                            $this->answer('mess', 'Не хватает инструментов!');
                        }
                    }

                    if ($this->userItemsExist($this->crafts[ $this->id ]['craft_items'], $this->colvo))
                    {
                        $allItemsFromInvent = $this->getAllItemsFromInvent();

                        foreach($allItemsFromInvent as $itemFromInvent)
                        {
                            $substrColvo = 0;

                            foreach($this->crafts[ $this->id ]['craft_items'] as $craftItem)
                            {
                                if ($craftItem['item'] == $itemFromInvent['item'] && $craftItem['type'] == $itemFromInvent['type'])
                                {
                                    $substrColvo = $craftItem['colvo'];
                                    break;
                                }
                            }

                            $this->itemSubstr($itemFromInvent['id'], $itemFromInvent['colvo'], $substrColvo);
                        }

                        $createdItemInInvent = $this->srchItemInInventWithoutId($this->crafts[ $this->id ]['type'], $this->crafts[ $this->id ]['item']);

                        if ($createdItemInInvent)
                        {
                            $this->itemAddMore($createdItemInInvent['id'], $createdItemInInvent['colvo'], $this->colvo);
                        } else
                        {
                            $this->createItem($this->crafts[ $this->id ]['item'], $this->crafts[ $this->id ]['type'], $this->colvo, 0);
                        }

                        $this->answer('mess', 'Предмет успешно создан!');
                    } else
                    {
                        $this->answer('mess', 'Недостаточно ресурсов!');
                    }
                }
            }
        }

        public function srchItemInItems($type, $item)
        {
            return $this->items[ $type ][ $item ];
        }

        public function upUserCraftLvl($craftLvl)
        {
            $this->pdo->query('UPDATE users SET `craft_lvl` = ? WHERE `id` = ?', array($craftLvl, $this->user['id']));
        }

        public function read()
        {
            $inventItem = $this->srchItemInInvent($this->idItem);

            if ($inventItem)
            {
                $item = $this->srchItemInItems($inventItem['type'], $inventItem['item']);

                if ($item['craft_lvl'] < $this->user['craft_lvl'])
                {
                    $this->answer('mess', 'Вы уже открыли данный уровень!');
                } else if ($item['craft_lvl'] !== ($this->user['craft_lvl'] + 1) && $item['craft_lvl'] !== $this->user['craft_lvl'])
                {
                    $this->answer('mess', 'Откройте предыдущий ур. крафта');
                } else
                {
                    $this->itemSubstr($inventItem['id'], $inventItem['colvo'], 1);
                    $this->upUserCraftLvl($item['craft_lvl']);
                    $this->toInventOrReload($inventItem['colvo']);
                }
            }
        }

        public function getUserRefuge()
        {
            return $this->pdo->fetch('SELECT * FROM `refuge` WHERE `user_id` = ?', array($this->user['id']));
        }

        public function enter()
        {
            $refuge = $this->getUserRefuge();

            if ($refuge['lvl'] > 0)
            {
                if ($this->user['in_refuge'])
                {
                    $this->pdo->query('UPDATE users SET `in_refuge` = ? WHERE `id` = ?', array(0, $this->user['id']));
                } else
                {
                    $this->pdo->query('UPDATE users SET `in_refuge` = ? WHERE `id` = ?', array(1, $this->user['id']));
                }

                $this->answer('reload', 0);
            }
        }

        public function upRefuge()
        {
            $refuge = $this->getUserRefuge();

            if ($this->refuges[ $refuge['lvl'] + 1 ])
            {
                if ($this->userItemsExist($this->refuges[ $refuge['lvl'] + 1 ]['craft_items'], 1))
                {
                    $allItemsFromInvent = $this->getAllItemsFromInvent();

                    foreach($allItemsFromInvent as $itemFromInvent)
                    {
                        $substrColvo = 0;

                        foreach($this->refuges[ $refuge['lvl'] + 1 ]['craft_items'] as $craftItem)
                        {
                            if ($craftItem['item'] == $itemFromInvent['item'] && $craftItem['type'] == $itemFromInvent['type'])
                            {
                                $substrColvo = $craftItem['colvo'];
                                break;
                            }
                        }

                        $this->itemSubstr($itemFromInvent['id'], $itemFromInvent['colvo'], $substrColvo);
                    }

                    $this->pdo->query('UPDATE refuge SET `lvl` = ?, `hp` = ? WHERE `user_id` = ?', array($refuge['lvl'] + 1, $this->refuges[ $refuge['lvl'] + 1 ]['maxhp'], $this->user['id']));

                    $this->answer('reload', 0);
                } else
                {
                    $this->answer('mess', 'Недостаточно ресурсов!');
                }
            } else
            {
                $this->answer('mess', 'Максимальный уровень');
            }
        }

        public function getUserItemsFromSlots()
        {
            return $this->pdo->fetchAll('SELECT * FROM `slots` WHERE `item` > 0 AND `type` > 0 AND `user_id` = ?', array($this->user['id']));
        }

        public function refugeSlotsNotOcuppied($typeName, $itemsFromSlots, $maxSlots)
        {
            $colvo = 0;

            foreach($itemsFromSlots as $itemFromSlots)
            {
                switch($typeName)
                {
                    case 'tools':
                        if ($itemFromSlots['type'] == 5)
                        {
                            $colvo += 1;
                        }
                        break;
                    case 'prots':
                        if ($itemFromSlots['type'] == 6)
                        {
                            $colvo += 1;
                        }
                        break;
                }
            }

            if ($colvo < $maxSlots)
            {
                return true;
            }
        }

        public function toolAlreadyThereInSlots($tool, $itemsFromSlots)
        {
            foreach($itemsFromSlots as $itemFromSlots)
            {
                if ($itemFromSlots['item'] == $tool['item'] && $itemFromSlots['type'] == $tool['type']) return true;
            }
        }

        public function srchEmptySlot()
        {
            return $this->pdo->fetch('SELECT * FROM `slots` WHERE `item` = 0 AND `type` = 0 AND `user_id` = ?', array($this->user['id']));
        }

        public function updateEmptySlot($item, $type, $id)
        {
            $this->pdo->query('UPDATE slots SET `item` = ?, `type` = ? WHERE `id` = ?', array($item, $type, $id));
        }

        public function createSlotWithItem($item, $type)
        {
            $this->pdo->query('INSERT INTO slots (item, type, user_id) VALUES (?, ?, ?)', array($item, $type, $this->user['id']));
        }

        public function place()
        {
            $inventItem = $this->srchItemInInvent($this->idItem);
            $item       = $this->srchItemInItems($inventItem['type'], $inventItem['item']);
            $refuge     = $this->getUserRefuge();
            $itemsFromSlots = $this->getUserItemsFromSlots();

            if ($this->refuges[ $refuge['lvl'] ][ $item['type_nm'] ] > 0) // Если слоты убежища больше нуля
            {
                if ($this->refugeSlotsNotOcuppied( $item['type_nm'], $itemsFromSlots, $this->refuges[ $refuge['lvl'] ][ $item['type_nm'] ] ))
                {
                    // Если тип надеваемого предмета Инструменты, и если в слотах уже есть данный предмет
                    if ($item['type_nm'] == 'tools' && $this->toolAlreadyThereInSlots($inventItem, $itemsFromSlots))
                    {
                        $this->answer('mess', 'Данный предмет уже есть!');
                    }

                    if ($emptySlot = $this->srchEmptySlot()) // Помещаем предмет в слот
                    {
                        $this->updateEmptySlot($inventItem['item'], $inventItem['type'], $emptySlot['id']);
                    } else
                    {
                        $this->createSlotWithItem($inventItem['item'], $inventItem['type']);
                    }

                    $this->itemSubstr($inventItem['id'], $inventItem['colvo'], 1); // Вычитаем помещаемый предмет из инвентаря
                    $this->toInventOrReload($inventItem['colvo']);
                } else
                {
                    $this->answer('mess', 'Все слоты заняты');
                }
            } else
            {
                $this->answer('mess', 'Улучшите убежище');
            }
        }

        public function locateUserToPage($page)
        {
            exit(json_encode(['page' => $page]));
        }

        public function answer($type, $message)
        {
            switch($type)
            {
                case 'mess':
                    exit( json_encode( ['message' => $message, 'popup' => true] ) );
                    break;
                case 'reload':
                    exit( json_encode( ['reload' => true] ) );
                    break;
            }
        }

    	public function main()
        {
            $this->idItem = (isset($_POST['id_item'])) ? $_POST['id_item'] : 0 ;

            switch($_GET['action'])
            {
                case 'srchloc':
                    //
                    break;
                case 'srchlut':
                    //
                break;
                case 'eat':
                    $this->eat();
                    break;
                case 'drink':
                    $this->drink();
                    break;
                case 'nadet':
                    $this->itemTyps = [2 => 'helm', 3 => 'arm', 4 => 'weap'];

                    $this->nadet();
                    break;
                case 'craft':
                    $this->id    = intval( $_POST['id'] );
                    $this->colvo = intval( $_POST['colvo'] );

                    $this->craft();
                    break;
                case 'read':
                    $this->read();
                break;
                case 'enterrefuge':
                    $this->enter();
                    break;
                case 'uprefuge':
                    $this->upRefuge();
                    break;
                case 'place':
                    $this->place();
                    break;
            }
    	}

    }

    if ($Utils::checkSession() && $Utils::checkToken())
    {
        $GameActions = new GameActions($Pdo, $game_items, $game_locs, $game_crafts, $game_weathers, $game_temps, $game_refuges, $User->getUser(), $User->getGame());
        $GameActions->main();
    }