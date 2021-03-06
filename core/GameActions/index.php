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
            return $this->pdo->fetch('SELECT * FROM `invent` WHERE `id` = ? AND `in_chest` = 0 AND `colvo` > 0 AND `user_id` = ?', array($idItem, $this->user['id']));
        }

        public function srchItemInChest($idItem)
        {
            return $this->pdo->fetch('SELECT * FROM `invent` WHERE `id` = ? AND `in_chest` = 1 AND `colvo` > 0 AND `user_id` = ?', array($idItem, $this->user['id']));
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

        public function createItem($item, $type, $colvo, $inChest)
        {
            $this->pdo->query('INSERT INTO invent (item, type, colvo, in_chest, user_id) VALUES (?, ?, ?, ?, ?)', array(
                $item, $type, $colvo, $inChest, $this->user['id']
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

        public function craft()
        {
            // Проверка соответсвия
            if ($this->crafts[ $this->id ]['item'] == $this->item && $this->crafts[ $this->id ]['type'] == $this->type) {
                if ($this->crafts[ $this->id ]['craft_lvl'] <= $this->user['craft_lvl']) {

                    // Проверка на соответсвие инструментов
                    if ($this->crafts[ $this->id ]['tools']) {
                        $tools_colvo = count($this->crafts[ $this->id ]['tools']);
                        $tools_exist = 0;
                        $tools       = $this->pdo->fetchAll('SELECT * FROM `slots` WHERE `item` > 0 AND `type` = 1 AND `user_id` = ?', array($this->user['id']));

                        // Проверяем все слоты инструментов игрока
                        foreach($tools as $t) {
                            for($i = 0; $i < $tools_colvo; $i++) {
                                if ($t['item'] == $this->crafts[ $this->id ]['tools'][ $i ]['item']) {
                                    $tools_exist += 1;
                                }
                            }
                        }

                        // Если у игрока нет требуемых инструментов
                        if ($tools_colvo !== $tools_exist) {
                            $this->message = 'Не хватает инструментов';
                            $this->answer('mess', 0);
                        }
                    }

                    $all_items   = count( $this->crafts[ $this->id ]['craft_items'] );
                    $all_exist   = 0;
                    $items       = array();
                    $items_colvo = array();
                    // Проверка на соответсвие предметов для крафта
                    foreach ($this->crafts[ $this->id ]['craft_items'] as $ci) {
                        $item = $this->pdo->fetch('SELECT * FROM `invent` WHERE `item` = ? AND `type` = ? AND `colvo` >= ? AND `user_id` = ?', array($ci['item'], $ci['type'], ($ci['colvo'] * $this->colvo), $this->user['id']));
                        if ($item) {
                            array_push($items, $item);
                            array_push($items_colvo, ($ci['colvo'] * $this->colvo));
                            $all_exist += 1;
                        }
                    }
                    // Если общее кол-во нужных предметов совпадет с проверенными
                    if ($all_items == $all_exist) {
                        for($i = 0; $i < count( $items ); $i++) {
                            // Убераем из инвентаря необходимые вещи для крафта
                            $this->pdo->query('UPDATE invent SET `colvo` = ? WHERE `item` = ? AND `type` = ? AND `in_chest` = 0 AND `user_id` = ?', array(($items[$i]['colvo'] - $items_colvo[ $i ]), $items[$i]['item'], $items[$i]['type'], $this->user['id']));
                        }
                        // Добавляем создаваемый предмет в инвентарь
                        $item = $this->pdo->fetch('SELECT * FROM `invent` WHERE `item` = ? AND `type` = ? AND `in_chest` = 0 AND `user_id` = ?', array($this->item, $this->type, $this->user['id']));
                        if ($item) {
                            $this->pdo->query('UPDATE invent SET `colvo` = ? WHERE `item` = ? AND `type` = ? AND `user_id` = ?', array(($item['colvo'] + $this->colvo), $this->item, $this->type, $this->user['id']));
                        } else {
                            $this->pdo->query('INSERT INTO invent (item, type, colvo, user_id) VALUES (?, ?, ?, ?)', array($this->item, $this->type, $this->colvo, $this->user['id']));
                        }

                        $this->message = 'Предмет успешно создан!';
                        $this->answer('mess', 0);
                    } else {
                        $this->message = 'Недостаточно ресурсов';
                        $this->answer('mess', 0);
                    }

                }
            }
        }

        public function read()
        {
            if ($this->idItem) {
                $item_invent = $this->pdo->fetch('SELECT * FROM `invent` WHERE `id` = ? AND `in_chest` = 0 AND `user_id` = ?', array($this->idItem, $this->user['id']));
                if ($item_invent) {
                    $item = $this->items[ $item_invent['type'] ][ $item_invent['item'] ];
                    if ($item['craft_lvl'] < $this->user['craft_lvl']) {
                        $this->message = 'Вы уже открыли данный уровень!';
                        $this->answer('mess', 0);
                    } else if ($item['craft_lvl'] !== ($this->user['craft_lvl'] + 1) && $item['craft_lvl'] !== $this->user['craft_lvl']) {
                        $this->message = 'Откройте предыдущий ур. крафта';
                        $this->answer('mess', 0);
                    } else {
                        $this->pdo->query('UPDATE invent SET `colvo` = ? WHERE `id` = ?', array(($item_invent['colvo'] - 1), $item_invent['id']));
                        $this->pdo->query('UPDATE users SET `craft_lvl` = ? WHERE `id` = ?', array($item['craft_lvl'], $this->user['id']));

                        if ($item_invent['colvo'] == 1) {
                            $this->locateUserToPage('page', 'invent');
                        } else $this->answer('reload', 0);
                    }
                }
            }
        }

        public function enter()
        {
            $refuge = $this->pdo->fetch('SELECT * FROM `refuge` WHERE `user_id` = ?', array($this->user['id']));

            if ($refuge['lvl'] > 0) {
                if ($this->user['in_refuge']) {
                    $this->pdo->query('UPDATE users SET `in_refuge` = ? WHERE `id` = ?', array(0, $this->user['id']));
                } else {
                    $this->pdo->query('UPDATE users SET `in_refuge` = ? WHERE `id` = ?', array(1, $this->user['id']));
                }

                $this->answer('reload', 0);
            }
        }

        public function upRefuge()
        {
            $refuge = $this->pdo->fetch('SELECT * FROM `refuge` WHERE `user_id` = ?', array($this->user['id']));

            if ($this->refuges[ $refuge['lvl'] + 1 ]) {
                $all_items = count($this->refuges[ $refuge['lvl'] + 1 ]['craft_items']);
                $all_exist = 0;
                $items       = array();
                $items_colvo = array();

                // Проверка на соответсвие предметов
                foreach ($this->refuges[ $refuge['lvl'] + 1 ]['craft_items'] as $ci) {
                    $item = $this->pdo->fetch('SELECT * FROM `invent` WHERE `item` = ? AND `type` = ? AND `in_chest` = 0 AND `colvo` >= ? AND `user_id` = ?', array($ci['item'], $ci['type'], $ci['colvo'], $this->user['id']));
                    if ($item) {
                        array_push($items, $item);
                        array_push($items_colvo, $ci['colvo']);
                        $all_exist += 1;
                    }
                }
                // Если общее кол-во нужных предметов совпадет с проверенными
                if ($all_items == $all_exist) {
                    for($i = 0; $i < count( $items ); $i++) {
                        // Убераем из инвентаря необходимые вещи для крафта
                        $this->pdo->query('UPDATE invent SET `colvo` = ? WHERE `item` = ? AND `type` = ? AND `in_chest` = 0 AND `user_id` = ?', array(($items[$i]['colvo'] - $items_colvo[ $i ]), $items[$i]['item'], $items[$i]['type'], $this->user['id']));
                    }

                    // Повышаем уровень убежища
                    $this->pdo->query('UPDATE refuge SET `lvl` = ?, `hp` = ? WHERE `user_id` = ?', array($refuge['lvl'] + 1, $this->refuges[ $refuge['lvl'] + 1 ]['maxhp'], $this->user['id']));

                    $this->message = 'Успешно!';
                    $this->answer('mess', 0);
                } else {
                    $this->message = 'Недостаточно ресурсов';
                    $this->answer('mess', 0);
                }
            } else {
                $this->message = 'Максимальный уровень';
                $this->answer('mess', 0);
            }
        }

        public function place()
        {
            if ($this->idItem)
            {
                $invent_item = $this->pdo->fetch('SELECT * FROM `invent` WHERE `id` = ? AND `user_id` = ?', array($this->idItem, $this->user['id']));
                $item        = $this->items[ $invent_item['type'] ][ $invent_item['item'] ];
                $refuge      = $this->pdo->fetch('SELECT * FROM `refuge` WHERE `user_id` = ?', array($this->user['id']));

                $slots       = $this->pdo->fetchAll('SELECT * FROM `slots` WHERE `item` > 0 AND `user_id` = ?', array($this->user['id']));
                $slots_elems = ['tools' => array(), 'prot' => array()];
                $type_name   = ($item['reftype'] == 1) ? 'tools' : 'prot';
                foreach($slots as $s)
                {
                    switch($s['type'])
                    {
                        case 1:
                        array_push($slots_elems['tools'], $s);
                            break;
                        case 2:
                        array_push($slots_elems['prot'], $s);
                            break;
                    }
                }

                if ($this->refuges[ $refuge['lvl'] ][ $type_name ] > 0) {
                    if (count($slots_elems[ $type_name ]) < $this->refuges[ $refuge['lvl'] ][ $type_name ]) {

                        // Если тип надеваемого предмета Инструменты
                        if ($item['reftype'] == 1) {
                            // Проверяем, есть ли такой предмет уже в слотах или нет
                            foreach($slots_elems[ $type_name ] as $se) {
                                if ($se['item'] == $invent_item['item']) {
                                    $this->message = 'Данный предмет уже помещен';
                                    $this->answer('mess', 0);
                                }
                            }
                        }

                        // Помещаем предмет в слот
                        $find_slot = $this->pdo->fetch('SELECT * FROM `slots` WHERE `item` = 0 AND `type` = ? AND `user_id` = ?', array($item['reftype'], $this->user['id']));
                        if ($find_slot) {
                            $this->pdo->query('UPDATE slots SET `item` = ? WHERE `id` = ? AND `user_id` = ?', array($invent_item['item'], $find_slot['id'], $this->user['id']));
                        } else {
                            $this->pdo->query('INSERT INTO slots (item, type, user_id) VALUES (?, ?, ?)', array($invent_item['item'], $item['reftype'], $this->user['id']));
                        }

                        // Вычитаем помещаемый предмет из инвентаря
                        $this->pdo->query('UPDATE invent SET `colvo` = ? WHERE `id` = ? AND `user_id` = ?', array(($invent_item['colvo'] - 1), $invent_item['id'], $this->user['id']));

                        if ($invent_item['colvo'] == 1) {
                            $this->locateUserToPage('page', 'invent');
                        } else $this->answer('reload', 0);
                    } else {
                        $this->message = 'Все слоты заняты';
                        $this->answer('mess', 0);
                    }
                } else {
                    $this->message = 'Улучшите убежище';
                    $this->answer('mess', 0);
                }
            }
        }

        public function placeToChest()
        {
            if ($this->idItem)
            {
                // Ищем помещаемый в сундук предмет в инвентаре
                $invent_item   = $this->pdo->fetch('SELECT * FROM `invent` WHERE `id` = ? AND `user_id` = ?', array($this->idItem, $this->user['id']));
                // Ищем все слоты из сундука
                $chest_full    = $this->pdo->rows('SELECT * FROM `invent` WHERE `colvo` > 0 AND `in_chest` > 0 AND `user_id` = ?', array($this->user['id']));
                // Ищем сундук в слотах убежища чтобы проверить есть ли вообще сундук у игрока
                $chest         = $this->pdo->fetch('SELECT * FROM `slots` WHERE `item` = ? AND `type` = ? AND `user_id` = ?', array(1, 1, $this->user['id']));
                // Ищем такой же предмет чтобы просто добавить к нему кол-во
                $item_in_chest = $this->pdo->fetch('SELECT * FROM `invent` WHERE `item` = ? AND `type` = ? AND `in_chest` > 0 AND `user_id` = ?', array($invent_item['item'], $invent_item['type'], $this->user['id']));

                if ($chest_full < 50 && $chest) {
                    if ($item_in_chest) {
                        // Изменяем кол-во помещаемого предмета на ноль
                        $this->pdo->query('UPDATE invent SET `colvo` = ? WHERE `id` = ?', array(0, $invent_item['id']));
                        // Изменяем кол-во предмета в сундуке (прибавляем из инвентаря)
                        $this->pdo->query('UPDATE invent SET `colvo` = ? WHERE `id` = ?', array(($invent_item['colvo'] + $item_in_chest['colvo']), $item_in_chest['id']));
                    } else {
                        $this->pdo->query('UPDATE invent SET `in_chest` = ? WHERE `id` = ?', array(1, $invent_item['id']));
                    }

                    $this->locateUserToPage('page', 'invent');
                }
            }
        }

        public function getFromChest()
        {
            if ($this->idItem)
            {
                // Ищем помещаемый в инвентарь предмет в инвентаре
                $invent_item    = $this->pdo->fetch('SELECT * FROM `invent` WHERE `id` = ? AND `user_id` = ?', array($this->idItem, $this->user['id']));
                // Ищем все слоты из инвентаря
                $invent_full    = $this->pdo->rows('SELECT * FROM `invent` WHERE `colvo` > 0 AND `in_chest` = 0 AND `user_id` = ?', array($this->user['id']));
                // Ищем такой же предмет чтобы просто добавить к нему кол-во
                $item_in_invent = $this->pdo->fetch('SELECT * FROM `invent` WHERE `item` = ? AND `type` = ? AND `in_chest` = 0 AND `user_id` = ?', array($invent_item['item'], $invent_item['type'], $this->user['id']));

                if ($invent_full < 50) {
                    if ($item_in_invent) {
                        // Изменяем кол-во помещаемого предмета на ноль
                        $this->pdo->query('UPDATE invent SET `colvo` = ? WHERE `id` = ?', array(0, $invent_item['id']));
                        // Изменяем кол-во предмета в инвентаре (прибавляем из сундука)
                        $this->pdo->query('UPDATE invent SET `colvo` = ? WHERE `id` = ?', array(($invent_item['colvo'] + $item_in_invent['colvo']), $item_in_invent['id']));
                    } else {
                        $this->pdo->query('UPDATE invent SET `in_chest` = 0 WHERE `id` = ?', array($invent_item['id']));
                    }

                    $this->locateUserToPage('page', 'invent');
                } else {
                    $this->message = 'Инвентарь полон';
                    $this->answer('mess', 0);
                }
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
                    $this->item  = intval( $_POST['item'] );
                    $this->type  = intval( $_POST['type'] );
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
                case 'placetochest':
                    $this->placeToChest();
                    break;
                case 'getfromchest':
                    $this->getFromChest();
                    break;
            }
    	}

    }

    if ($Utils::checkSession() && $Utils::checkToken())
    {
        $GameActions = new GameActions($Pdo, $game_items, $game_locs, $game_crafts, $game_weathers, $game_temps, $game_refuges, $User->getUser(), $User->getGame());
        $GameActions->main();
    }