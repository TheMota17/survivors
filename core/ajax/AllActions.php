<?php
	require realpath('../User.php');
    require realpath('../gamedata.php');

    Class AllActions {

    	public function __construct($pdo, $items, $locs, $action_times, $crafts, $weathers, $temps, $refuges, $user, $game)
    	{
            
            $this->pdo = $pdo;

            $this->items        = $items;
            $this->locs         = $locs;
            $this->action_times = $action_times;
            $this->crafts       = $crafts;
            $this->weathers     = $weathers;
            $this->temps        = $temps;
            $this->refuges      = $refuges;

    		$this->user = $user;
            $this->game = $game;
            
    	}

        public function item_substr($id_item, $colvo) {

            $substr = $this->pdo->query('UPDATE `invent` SET `colvo` = ? WHERE `id` = ?', array(($colvo - 1), $id_item));

        }

        public function type( $type ) {

            switch( $type ) {
                case 2:
                    return 'helm';
                    break;
                case 3:
                    return 'arm';
                    break;
                case 4:
                    return 'weap';
                    break;
            }

        }

        public function item_drop($move, $item, $night) {

            switch( $move ) {
                case 'isdrop':
                    if (mt_rand(1, $this->items[ $item['t'] ][ $item['i'] ]['chance']) == 1) return true;
                break;
                case 'colvo':
                    if ( $night ) return 1;
                    else return mt_rand(1, $this->items[ $item['t'] ][ $item['i'] ][ 'colvo' ]);
                break;
            }

        }

        public function night() {

            if ($this->user['user_time'] >= 75600 || $this->user['user_time'] <= 18000 ) return true;

        }

        public function user_die() {

            if ($this->user['live'] == 0) {

                $this->pdo->query('UPDATE users SET `live` = ?, `hp` = ?, `hung` = ?, `thirst` = ?, `fatigue` = ?, `user_time` = ?, `user_weather` = ?, `user_temp` = ?, `loc` = ?, `loc_explored` = ?',
                array(3, 100, 0, 0, 0, 35600, 1, 1, 1, 0));

                $invent_items = $this->pdo->fetchAll('SELECT * FROM `invent` WHERE `user_id` = ? AND `colvo` > 0', array($this->user['id']));
                foreach($invent_items as $iv) {
                    $this->pdo->query('UPDATE invent SET `colvo` = ? WHERE `user_id` = ?', array(0, $this->user['id']));
                }

                $this->pdo->query('UPDATE nadeto SET `helm` = ?, `arm` = ?, `weap` = ? WHERE `id` = ?', array(0, 0, 0, $this->user['id']));

                $this->message = 'Вы чуть не умерли, ваше везение не знает границ! Потрепанные после нескольких часов без сознания, вы просыпаетесь, и к сожалению вы обнаруживаете что, все ваши вещи были украдены...';
                $this->answer('mess', 0);

            }

        }

        public function change_hp( $time ) {

            $hung_hp    = 0;
            $thirst_hp  = 0;
            $fatigue_hp = 0;
            $temp_hp    = 0;
            $total      = 0;

            if ($this->user['hung'] >= 100)    $hung_hp    = intval($time / 600);
            if ($this->user['thirst'] >= 100)  $thirst_hp  = intval($time / 600);
            if ($this->user['fatigue'] >= 100) $fatigue_hp = intval($time / 900);

            if ( $this->user['in_refuge'] == 0 ) {
                switch( $this->user['user_temp'] ) {
                    case 3: $temp_hp = intval($time / 900); break;
                    case 4: $temp_hp = intval($time / 600); break;
                }
            } 

            $total = $hung_hp + $thirst_hp + $fatigue_hp + $temp_hp;

            if ($total > 0) {
                if ($total >= $this->user['hp']) {
                    if ($this->user['live'] > 0) {
                        $this->pdo->query('UPDATE users SET `live` = ?, `hp` = ? WHERE `id` = ?', array(($this->user['live'] - 1), 100, $this->user['id']));
                    } else {
                        $this->user_die();
                    }
                } else {
                    $this->pdo->query('UPDATE users SET `hp` = ? WHERE `id` = ?', array($this->user['hp'] - $total, $this->user['id']));
                }
            }

        }

        public function change_charact($time, $action) {

            $hung    = intval( $time / 300 );
            $thirst  = intval( $time / 360 );
            $fatigue = intval( $time / 600 );

            $hung    = $this->user['hung']    + $hung;
            $thirst  = $this->user['thirst']  + $thirst;
            $fatigue = $this->user['fatigue'] + $fatigue;

            if ($hung > 100) $hung       = 100;
            if ($thirst > 100) $thirst   = 100;
            if ($fatigue > 100) $fatigue = 100;

            $sql = 'UPDATE `users` SET `hung` = ?, `thirst` = ?, `fatigue` = ? WHERE `id` = ?';
            $arr = [$hung, $thirst, $fatigue, $this->user['id']];

            $this->pdo->query($sql, $arr);

        }

        public function change_weather( $time ) {

            if ($time >= 600) {
                $weather_change_percent = mt_rand(1, 5); // Если мы получим 5, то меняем погоду
                if ($weather_change_percent == 5) {
                    switch($this->user['user_weather']) {
                        case 1:
                            $weathers     = [2, 4];
                            $rand_weather = mt_rand(0, 1);
                            break;
                        case 2:
                            $weathers     = [1, 3];
                            $rand_weather = mt_rand(0, 1);
                            break;
                        case 3:
                            $weathers     = [1, 2];
                            $rand_weather = mt_rand(0, 1);
                            break;
                        case 4:
                            $weathers     = [2, 5];
                            $rand_weather = mt_rand(0, 1);
                            break;
                        case 5:
                            $weathers     = [1, 4];
                            $rand_weather = mt_rand(0, 1);
                            break;
                    }

                    $this->change_temp( $weathers[ $rand_weather ] );
                    $this->pdo->query('UPDATE users SET `user_weather` = ? WHERE `id` = ?', array($weathers[ $rand_weather ], $this->user['id']));
                }
            }

        }

        public function change_temp( $weather ) {

            switch( $weather ) {
                case 1:
                    $temps     = [1, 2];
                    $rand_temp = mt_rand(0, 1);
                    break;
                case 2:
                    $temps     = [2, 3];
                    $rand_temp = mt_rand(0, 1);
                    break;
                case 3:
                    $temps     = [3, 4];
                    $rand_temp = mt_rand(0, 1);
                    break;
                case 4:
                    $temps     = [3, 3];
                    $rand_temp = mt_rand(0, 1);
                    break;
                case 5:
                    $temps     = [3, 4];
                    $rand_temp = mt_rand(0, 1);
                    break;
            }

            $this->pdo->query('UPDATE users SET `user_temp` = ? WHERE `id` = ?', array($temps[ $rand_temp ], $this->user['id']));

        }

        public function change_explored() {

            $rand_percent = mt_rand(1, 4);
            $rand_percent = $this->user['loc_explored'] + $rand_percent;

            if ($rand_percent > 100) $rand_percent = 100;

            $this->pdo->query('UPDATE `users` SET `loc_explored` = ? WHERE `id`= ?', array(
                $rand_percent,
                $this->user['id']
            ));

        }

        public function action_times($action, $time) {

            if (! $time) {
                $time = mt_rand($this->action_times[ $action ]['min'], $this->action_times[ $action ]['max']);
            }

            switch( $this->user['user_weather'] ) {
                case 3:
                    $time += $this->weathers[ $this->user['user_weather'] ]['bonustime'];
                    break;
                case 4:
                    $time += $this->weathers[ $this->user['user_weather'] ]['bonustime'];
                    break;
            }

            if ( ($time + $this->user['user_time']) >= 86400) {
                $time = $time + $this->user['user_time'];
                $time = $time - 86400;

                $this->pdo->query('UPDATE `users` SET `user_time` = ? WHERE `id` = ?', array($time, $this->user['id']));
            } else {
                $this->pdo->query('UPDATE `users` SET `user_time` = ? WHERE `id` = ?', array( ($this->user['user_time'] + $time), $this->user['id']));
            }

            $this->change_charact($time, $action);
            $this->change_hp( $time );
            $this->change_weather( $time );

        }

        public function srchLoc() {
            
            if ($this->user['in_refuge'] > 0) {
                $this->message = 'Вы в убежище';
                $this->answer('mess', 0);
            } else if ($this->user['fatigue'] >= 100) {
                $this->message = 'Вы очень устали';
                $this->answer('mess', 0);
            } else {

                $rand = mt_rand(1, (count($this->locs)));
                if ($rand !== 1 && $rand <= (count($this->locs))) {

                    $this->pdo->query('UPDATE `users` SET `loc` = ?, `loc_explored` = ? WHERE `id` = ?', array($this->locs[ $rand ][ 'id' ], 0, $this->user['id']));
                    $this->message = 'Вы нашли - '.$this->locs[ $rand ]['nm'].'';
                    $this->answer('mess', 0);
                } else {

                    $this->pdo->query('UPDATE `users` SET `loc` = ?, `loc_explored` = ? WHERE `id` = ?', array(1, 0, $this->user['id']));
                    $this->message = 'Вы ничего не нашли';
                    $this->answer('mess', 0);
                }

                $this->action_times('srchloc', 0);
            }

        }

        public function srchLut() {

            $invent_cells = $this->pdo->rows('SELECT * FROM `invent` WHERE `colvo` > 0 AND `user_id` = ?', array($this->user['id']));
            if ($this->user['in_refuge'] > 0) {
                $this->message = 'Вы в убежище';
                $this->answer('mess', 0);
            } else if ($invent_cells >= 50) {
                $this->message = 'Инвентарь полон';
                $this->answer('mess', 0);
            } else if ($this->user['fatigue'] >= 100) {
                $this->message = 'Вы очень устали';
                $this->answer('mess', 0);
            } else if ($this->user['loc_explored'] >= 100) {
                $this->message = 'Локация полностью исследована';
                $this->answer('mess', 0);
            } else {
                $night          = $this->night();
                $items          = array();
                $rand_colvo     = array();
                $this->message .= '<div class=\'flex j-s\'>- Вы нашли</div><div class=\'srched-items flex j-c mt10\'>';

                for($i = 0; $i < count( $this->locs[ $this->user['loc'] ]['srch_items'] ); $i++) {
                    if ( $this->item_drop('isdrop', $this->locs[ $this->user['loc'] ]['srch_items'][ $i ], 0) ) {
                        array_push($items, $this->locs[ $this->user['loc'] ]['srch_items'][ $i ]);

                        $colvo = $this->item_drop('colvo', $this->locs[ $this->user['loc'] ]['srch_items'][ $i ], $night);
                        array_push($rand_colvo, $colvo);

                        $this->message .= '
                        <div class=\'srched-item relative flex j-s ai-c fl-di-co mr10\'>
                            <div class=\'item32-2 flex j-c ai-c\'>
                                <img src=\''.$this->items[ $this->locs[$this->user['loc']]['srch_items'][$i]['t'] ][ $this->locs[$this->user['loc']]['srch_items'][$i]['i'] ]['img'].'\' />
                            </div>
                            <div class=\'item-colvo-min flex j-c ai-c\'>
                                '.$colvo.'
                            </div>
                        </div>';
                    }
                }

                for($i = 0; $i < count($items); $i++) {
                    $item_in_invent = $this->pdo->fetch('SELECT * FROM `invent` WHERE `item` = ? AND `type` = ? AND `user_id` = ?', array($items[ $i ]['i'], $items[ $i ]['t'], $this->user['id']));
                    if ($item_in_invent) {
                        $this->pdo->query('UPDATE invent SET `colvo` = ? WHERE `id` = ? AND `user_id` = ?', array(($item_in_invent['colvo'] + $rand_colvo[ $i ]), $item_in_invent['id'], $this->user['id']));
                    } else {
                        $this->pdo->query('INSERT INTO invent (item, type, colvo, user_id) VALUES (?, ?, ?, ?)', array($items[ $i ]['i'], $items[ $i ]['t'], $rand_colvo[ $i ], $this->user['id']));
                    }
                }

                $this->action_times('srchlut', 0);
                if ($this->user['loc'] !== 1) $this->change_explored();

                $this->message .= '</div>';
                $this->answer('mess', 0);
            }

        }

        public function eat() {

            if ($this->id_item) $item = $this->pdo->fetch('SELECT * FROM `invent` WHERE `id` = ? AND `user_id` = ?', array($this->id_item, $this->user['id']));

            if ($item && $item['colvo'] > 0) {
                if ($this->game->hung < $this->items[ 1 ][ $item['item'] ]['eff']['hung']) {
                    $this->message = 'Вы не голодны';
                    $this->answer('mess', 0);
                } else {
                    $hp = (($this->game->hp + $this->items[ $item['type'] ][ $item['item'] ]['eff']['hp'] ) > 100) ? 100 : ($this->game->hp + $this->items[ $item['type'] ][ $item['item'] ]['eff']['hp']);
                    $this->game->hp   = $hp;
                    $this->game->hung = $this->game->hung - $this->items[ $item['type'] ][ $item['item'] ]['eff']['hung'];

                    $eat = $this->pdo->query('UPDATE `users` SET `game` = ? WHERE `id` = ?', array(json_encode($this->game), $this->user['id']));

                    $this->item_substr($item['id'], $item['colvo']);

                    if ($item['colvo'] == 1) {
                        $this->answer('page', 'invent');
                    } else $this->answer('reload', 0);
                }
            } else {
                $this->message = 'Недостаточно еды';
                $this->answer('mess', 0);
            }

        }

        public function drink() {

            if ($this->id_item) $item = $this->pdo->fetch('SELECT * FROM `invent` WHERE `id` = ? AND `user_id` = ?', array($this->id_item, $this->user['id']));

            if ($item && $item['colvo'] > 0) {
                if ($this->game->thirst < $this->items[ 1 ][ $item['item'] ]['eff']['thirst']) {
                    $this->message = 'Вы не хотите пить';
                    $this->answer('mess', 0);
                } else if ($item['colvo'] <= 0) {
                    $this->message = 'Недостаточно воды';
                    $this->answer('mess', 0);
                } else {
                    $hp = (($this->game->hp + $this->items[ $item['type'] ][ $item['item'] ]['eff']['hp'] ) > 100) ? 100 : ($this->game->hp + $this->items[ $item['type'] ][ $item['item'] ]['eff']['hp']);
                    $this->game->hp     = $hp;
                    $this->game->thirst = $this->game->thirst - $this->items[ $item['type'] ][ $item['item'] ]['eff']['thirst'];

                    $drink = $this->pdo->query('UPDATE `users` SET `game` = ? WHERE `id` = ?', array(json_encode($this->game), $this->user['id']));

                    $this->item_substr($item['id'], $item['colvo']);
                    if ($item['colvo'] == 1) {
                        $this->answer('page', 'invent');
                    } else $this->answer('reload', false);
                }
            } else {
                $this->message = 'Недостаточно воды';
                $this->answer('mess', 0);
            }

        }

        public function nadet() {
            
            if ($this->id_item) {
                // Надеваемый предмет из инвентаря
                $invent_item = $this->pdo->fetch('SELECT * FROM `invent` WHERE `id` = ? AND `in_chest` = 0 AND `user_id` = ?', array($this->id_item, $this->user['id']));
                // Все надетые предметы игрока
                $nadeto_items = $this->pdo->fetch('SELECT * FROM `nadeto` WHERE `user_id` = ?', array($this->user['id']));

                if ($invent_item['item'] == $nadeto_items[ $this->type($invent_item['type']) ]) {
                    $this->message = 'На вас уже надет данный предмет';
                    $this->answer('mess', 0);
                } else {
                    // Было ли надето на игрока вообще что то
                    if ($nadeto_items[ $this->type($invent_item['type']) ] > 0) {
                        // Ищем в инвентаре снимаемый предмет с игрока
                        $nadeto_item_invent = $this->pdo->fetch('SELECT * FROM `invent` WHERE `type` = ? AND `item` = ? AND `user_id` = ?',array($invent_item['type'], $nadeto_items[ $this->type($invent_item['type']) ], $this->user['id']));
                        if ($nadeto_item_invent) {
                            // Если предмет найден, то просто увеличиваем на единицу
                            $this->pdo->query('UPDATE `invent` SET `colvo` = ? WHERE `id` = ?', array(($nadeto_item_invent['colvo'] + 1), $nadeto_item_invent['id']));
                        } else {
                            // Если нет, то создаем новую запись для предмета
                            $this->pdo->query('INSERT INTO invent (item, type, colvo, in_chest, user_id) VALUES (?, ?, ?, ?, ?)', array($nadeto_items[ $this->type($invent_item['type']) ], $invent_item['type'], 1, 0, $this->user['id']));
                        }
                    }

                    // Надеваем предмет на игрока
                    $this->pdo->query('UPDATE `nadeto` SET `'.$this->type($invent_item['type']).'` = ? WHERE `user_id` = ?', array($invent_item['item'], $this->user['id']));
                    // Надеваемый предмет вычитаем из инвентаря
                    $this->pdo->query('UPDATE `invent` SET `colvo` = ? WHERE `id` = ?', array(($invent_item['colvo'] - 1), $invent_item['id']));
                }

                if ($invent_item['colvo'] == 1) {
                    $this->answer('page', 'invent');
                } else $this->answer('reload', 0);
            }

        }

        public function craft() {

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
                            $this->pdo->query('UPDATE invent SET `colvo` = ? WHERE `item` = ? AND `type` = ? AND `user_id` = ?', array(($items[$i]['colvo'] - $items_colvo[ $i ]), $items[$i]['item'], $items[$i]['type'], $this->user['id']));
                        }
                        // Добавляем создаваемый предмет в инвентарь
                        $item = $this->pdo->fetch('SELECT * FROM `invent` WHERE `item` = ? AND `type` = ? AND `user_id` = ?', array($this->item, $this->type, $this->user['id']));
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

        public function read() {

            if ($this->id_item) {

                $item_invent = $this->pdo->fetch('SELECT * FROM `invent` WHERE `id` = ? AND `user_id` = ?', array($this->id_item, $this->user['id']));
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
                            $this->answer('page', 'invent');
                        } else $this->answer('reload', 0);
                    }
                }

            }

        }

        public function enter() {

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

        public function upRefuge() {

            $refuge = $this->pdo->fetch('SELECT * FROM `refuge` WHERE `user_id` = ?', array($this->user['id']));

            if ($this->refuges[ $refuge['lvl'] + 1 ]) {
                $all_items = count($this->refuges[ $refuge['lvl'] + 1 ]['craft_items']);
                $all_exist = 0;
                $items       = array();
                $items_colvo = array();

                // Проверка на соответсвие предметов
                foreach ($this->refuges[ $refuge['lvl'] + 1 ]['craft_items'] as $ci) {
                    $item = $this->pdo->fetch('SELECT * FROM `invent` WHERE `item` = ? AND `type` = ? AND `colvo` >= ? AND `user_id` = ?', array($ci['item'], $ci['type'], $ci['colvo'], $this->user['id']));
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
                        $this->pdo->query('UPDATE invent SET `colvo` = ? WHERE `item` = ? AND `type` = ? AND `user_id` = ?', array(($items[$i]['colvo'] - $items_colvo[ $i ]), $items[$i]['item'], $items[$i]['type'], $this->user['id']));
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

        public function place() {

            if ($this->id_item) {
                $invent_item = $this->pdo->fetch('SELECT * FROM `invent` WHERE `id` = ? AND `user_id` = ?', array($this->id_item, $this->user['id']));
                $item        = $this->items[ $invent_item['type'] ][ $invent_item['item'] ];
                $refuge      = $this->pdo->fetch('SELECT * FROM `refuge` WHERE `user_id` = ?', array($this->user['id']));

                $slots       = $this->pdo->fetchAll('SELECT * FROM `slots` WHERE `item` > 0 AND `user_id` = ?', array($this->user['id']));
                $slots_elems = ['tools' => array(), 'prot' => array()];
                $type_name   = ($item['reftype'] == 1) ? 'tools' : 'prot';
                foreach($slots as $s) {
                    switch($s['type']) {
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
                            $this->answer('page', 'invent');
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

        public function placeToChest() {

            if ($this->id_item) {
                // Ищем помещаемый в сундук предмет в инвентаре
                $invent_item   = $this->pdo->fetch('SELECT * FROM `invent` WHERE `id` = ? AND `user_id` = ?', array($this->id_item, $this->user['id']));
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

                    $this->answer('page', 'invent');
                }
            }

        }

        public function getFromChest() {

            if ($this->id_item) {
                // Ищем помещаемый в инвентарь предмет в инвентаре
                $invent_item    = $this->pdo->fetch('SELECT * FROM `invent` WHERE `id` = ? AND `user_id` = ?', array($this->id_item, $this->user['id']));
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

                    $this->answer('page', 'invent');
                } else {
                    $this->message = 'Инвентарь полон';
                    $this->answer('mess', 0);
                }
            }

        }

        public function answer($ans, $page) {

            switch( $ans ) {
                case 'page':
                    exit( json_encode( ['page' => $page] ) );
                    break;
                case 'mess':
                    exit( json_encode( ['message' => $this->message, 'popup' => true] ) );
                    break;
                case 'reload':
                    exit( json_encode( ['reload' => true] ) );
                    break;
            }

        }

    	public function main() {

            switch($_GET['action']) {
                case 'srchloc': 
                    $this->srchLoc();
                    break;
                case 'srchlut':
                    $this->srchLut();
                break;
                case 'eat':
                    $this->id_item = htmlspecialchars( intval( $_POST['id_item'] ) );
                    $this->eat();
                    break;
                case 'drink':
                    $this->id_item = htmlspecialchars( intval( $_POST['id_item'] ) );
                    $this->drink();
                    break;
                case 'nadet':
                    $this->id_item = htmlspecialchars( intval( $_POST['id_item'] ) );
                    $this->nadet();
                    break;
                case 'craft':
                    $this->id    = htmlspecialchars( intval( $_POST['id'] ) );
                    $this->item  = htmlspecialchars( intval( $_POST['item'] ) );
                    $this->type  = htmlspecialchars( intval( $_POST['type'] ) );
                    $this->colvo = htmlspecialchars( intval( $_POST['colvo'] ) );
                    $this->craft();
                    break;
                case 'read':
                    $this->id_item = htmlspecialchars( intval( $_POST['id_item'] ) );
                    $this->read();
                break;
                case 'enterrefuge':
                    $this->enter();
                    break;
                case 'uprefuge':
                    $this->upRefuge();
                    break;
                case 'place':
                    $this->id_item = htmlspecialchars( intval( $_POST['id_item'] ) );
                    $this->place();
                    break;
                case 'placetochest':
                    $this->id_item = htmlspecialchars( intval( $_POST['id_item'] ) );
                    $this->placeToChest();
                    break;
                case 'getfromchest':
                    $this->id_item = htmlspecialchars( intval( $_POST['id_item'] ) );
                    $this->getFromChest();
                    break;
            }

    	}
        
    }

    if ($Utils::checkSession()) {
        $AllActions = new AllActions($Pdo, $game_items, $game_locs, $game_action_times, $game_crafts, $game_weathers, $game_temps, $game_refuges, $User->get_user(), $User->get_game());
        $AllActions->main();
    }