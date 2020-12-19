<?php
	require realpath('../sys.php');
    require realpath('../gamedata.php');

    Class GameActions {

    	public function __construct($pdo, $items, $locs, $action_times, $crafts, $weathers, $temps, $refuges, $user, $action)
    	{
            
            $this->pdo = $pdo;

            $this->items        = $items;
            $this->locs         = $locs;
            $this->action_times = $action_times;
            $this->crafts       = $crafts;
            $this->weathers     = $weathers;
            $this->temps        = $temps;
            $this->refuges      = $refuges;

    		$this->user    = $user;
            $this->action  = htmlspecialchars( $action );
            
    	}

        public function item_substr($id_item, $colvo) {

            $substr = $this->pdo->query('UPDATE `ivent` SET `colvo` = ? WHERE `id` = ?', array(($colvo - 1), $id_item));

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

                $ivent_items = $this->pdo->fetchAll('SELECT * FROM `ivent` WHERE `user_id` = ? AND `colvo` > 0', array($this->user['id']));
                foreach($ivent_items as $iv) {
                    $this->pdo->query('UPDATE ivent SET `colvo` = ? WHERE `user_id` = ?', array(0, $this->user['id']));
                }

                $this->pdo->query('UPDATE nadeto SET `helm` = ?, `arm` = ?, `weap` = ? WHERE `id` = ?', array(0, 0, 0, $this->user['id']));

                $this->message = '<div class=\'flex j-s ai-c\'>Вы чуть не умерли, ваше везение не знает границ! Потрепанные после нескольких часов без сознания, вы просыпаетесь, и к сожалению вы обнаруживаете что, все ваши вещи были украдены...</div>';
                $this->answer('messreload', 0);

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

                $this->hp_mess = '<div class=\'errors2 flex j-s ai-c mt5\'>- Вы теряете здоровье</div>';
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

            switch( $action ) {
                case 'sleep':
                    $sql = 'UPDATE `users` SET `hung` = ?, `thirst` = ? WHERE `id` = ?';
                    $arr = [$hung, $thirst, $this->user['id']];
                    break;
                default:
                    $sql = 'UPDATE `users` SET `hung` = ?, `thirst` = ?, `fatigue` = ? WHERE `id` = ?';
                    $arr = [$hung, $thirst, $fatigue, $this->user['id']];
                    break;
            }
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

                    $this->weather_mess = '<div class=\'flex j-s ai-c mt5\'>- '.$this->weathers[ $weathers[ $rand_weather ] ]['onchange'].'</div>';
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

            if ($this->temps[ $temps[ $rand_temp ] ]['alert']) {
                $this->temp_mess = '<div class=\'mess flex j-s ai-c mt5\'>- '.$this->temps[ $temps[ $rand_temp ] ]['alert'].'</div>';
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
 
        public function srch_loc() {
            
            if ($this->user['in_refuge'] > 0) {
                $this->message = '<div class=\'flex j-c ai-c\'>Вы в убежище</div>';
                $this->answer('mess', 0);
            } else if ($this->user['fatigue'] >= 100) {
                $this->message = '<div class=\'flex j-c ai-c\'>Вы очень устали</div>';
                $this->answer('mess', 0);
            } else {

                $rand = mt_rand(1, (count($this->locs)));
                if ($rand !== 1 && $rand <= (count($this->locs))) {

                    $this->pdo->query('UPDATE `users` SET `loc` = ?, `loc_explored` = ? WHERE `id` = ?', array($this->locs[ $rand ][ 'id' ], 0, $this->user['id']));
                    $this->message = '<div class=\'flex j-c ai-c\'>Вы нашли - '.$this->locs[ $rand ]['nm'].'</div>';
                    if ($this->formation_answer()) $this->answer('messreload', 0);
                } else {

                    $this->pdo->query('UPDATE `users` SET `loc` = ?, `loc_explored` = ? WHERE `id` = ?', array(1, 0, $this->user['id']));
                    $this->message = '<div class=\'flex j-c ai-c\'>Вы ничего не нашли</div>';
                    if ($this->formation_answer()) $this->answer('messreload', 0);
                }

                $this->action_times('srchloc', 0);
            }

        }

        public function srch_lut() {

            $ivent_cells = $this->pdo->rows('SELECT * FROM `ivent` WHERE `colvo` > 0 AND `user_id` = ?', array($this->user['id']));
            if ($this->user['in_refuge'] > 0) {
                $this->message = '<div class=\'flex j-c ai-c\'>Вы в убежище</div>';
                $this->answer('mess', 0);
            } else if ($ivent_cells >= 50) {
                $this->message = '<div class=\'flex j-c ai-c\'>Инвентарь полон</div>';
                $this->answer('mess', 0);
            } else if ($this->user['fatigue'] >= 100) {
                $this->message = '<div class=\'flex j-c ai-c\'>Вы очень устали</div>';
                $this->answer('mess', 0);
            } else if ($this->user['loc_explored'] >= 100) {
                $this->message = '<div class=\'flex j-c ai-c\'>Локация полностью исследована</div>';
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
                    $item_in_ivent = $this->pdo->fetch('SELECT * FROM `ivent` WHERE `item` = ? AND `type` = ? AND `user_id` = ?', array($items[ $i ]['i'], $items[ $i ]['t'], $this->user['id']));
                    if ($item_in_ivent) {
                        $this->pdo->query('UPDATE ivent SET `colvo` = ? WHERE `id` = ? AND `user_id` = ?', array(($item_in_ivent['colvo'] + $rand_colvo[ $i ]), $item_in_ivent['id'], $this->user['id']));
                    } else {
                        $this->pdo->query('INSERT INTO ivent (item, type, colvo, user_id) VALUES (?, ?, ?, ?)', array($items[ $i ]['i'], $items[ $i ]['t'], $rand_colvo[ $i ], $this->user['id']));
                    }
                }

                $this->action_times('srchlut', 0);
                if ($this->user['loc'] !== 1) $this->change_explored();

                $this->message .= '</div>';
                if ($this->formation_answer()) $this->answer('messreload', 0);
            }

        }

        public function eat() {

            $item;
            if ( $this->id_item )
                $item = $this->pdo->fetch('SELECT * FROM `ivent` WHERE `id` = ? AND `user_id` = ?', array($this->id_item, $this->user['id']));
            else
                $item = $this->pdo->fetch('SELECT * FROM `ivent` WHERE `item` = ? AND `type` = ? AND `colvo` > 0 AND `user_id` = ?', array(13, 1, $this->user['id']));

            if ($item && $item['colvo'] > 0) {

                if ($this->user['hung'] < $this->items[ 1 ][ $item['item'] ]['eff']['hung']) {
                    $this->message = '<div class=\'flex j-c ai-c\'>Вы не голодны</div>';
                    $this->answer('mess', 0);
                } else {
                    $hp  = (($this->user['hp'] + $this->items[ $item['type'] ][ $item['item'] ]['eff']['hp'] ) > 100) ? 100 : ($this->user['hp'] + $this->items[ $item['type'] ][ $item['item'] ]['eff']['hp']);
                    $eat = $this->pdo->query('UPDATE `users` SET `hung` = ?, `hp` = ? WHERE `id` = ?', array(
                        ($this->user['hung'] - $this->items[ $item['type'] ][ $item['item'] ]['eff'][ 'hung' ]), $hp, $this->user['id']
                    ));

                    $this->item_substr($item['id'], $item['colvo']);

                    if ($this->from == 'item' && $item['colvo'] == 1) {
                        $this->answer('page', '/ivent');
                    } else $this->answer('reload', 0);
                }

            } else {
                $this->message = '<div class=\'flex j-c ai-c\'>Недостаточно еды</div>';
                $this->answer('mess', 0);
            }

        }

        public function drink() {

            $item;
            if ( $this->id_item ) 
                $item = $this->pdo->fetch('SELECT * FROM `ivent` WHERE `id` = ? AND `user_id` = ?', array($this->id_item, $this->user['id']));
            else
                $item = $this->pdo->fetch('SELECT * FROM `ivent` WHERE `item` = ? AND `type` = ? AND `colvo` > 0 AND `user_id` = ?', array(2, 1, $this->user['id']));

            if ($item && $item['colvo'] > 0) {

                if ($this->user['thirst'] < $this->items[ 1 ][ $item['item'] ]['eff']['thirst']) {
                    $this->message = '<div class=\'flex j-c ai-c\'>Вы не хотите пить</div>';
                    $this->answer('mess', 0);
                } else if ($item['colvo'] <= 0) {
                    $this->message = '<div class=\'flex j-c ai-c\'>Недостаточно воды</div>';
                    $this->answer('mess', 0);
                } else {
                    $hp    = (($this->user['hp'] + $this->items[ $item['type'] ][ $item['item'] ]['eff']['hp'] ) > 100) ? 100 : ($this->user['hp'] + $this->items[ $item['type'] ][ $item['item'] ]['eff']['hp']);
                    $drink = $this->pdo->query('UPDATE `users` SET `thirst` = ?, `hp` = ? WHERE `id` = ?', array(
                        ($this->user['thirst'] - $this->items[ $item['type'] ][ $item['item'] ]['eff'][ 'thirst' ]), $hp, $this->user['id']
                    ));

                    $this->item_substr($item['id'], $item['colvo']);
                    if ($this->from == 'item' && $item['colvo'] == 1) {
                        $this->answer('page', '/ivent');
                    } else $this->answer('reload', false);
                }

            } else {
                $this->message = '<div class=\'flex j-c ai-c\'>Недостаточно воды</div>';
                $this->answer('mess', 0);
            }

        }

        public function sleep() {

            if ($this->user['fatigue'] !== 0 && $this->user['fatigue'] >= 10) {

                if ($this->hours > 0 && $this->hours < 24) {

                    $sleep_time = $this->hours * 10;
                    if (($this->user['fatigue'] - $sleep_time) < 0) $sleep_time = 0;
                    else $sleep_time = $this->user['fatigue'] - $sleep_time;

                    $this->pdo->query('UPDATE `users` SET `fatigue` = ? WHERE `id` = ?', array($sleep_time, $this->user['id']));

                    $this->action_times('sleep', ($this->hours * 3600));

                    $this->message = '<div class=\'flex j-s ai-c\'>- Вы проснулись</div>';
                    if ($this->formation_answer()) $this->answer('messreload', 0);
                }

            } else {
                $this->message = '<div class=\'flex j-c ai-c\'>Вы не хотите спать</div>';
                $this->answer('mess', 0);
            }

        }

        public function nadet() {
            
            if ($this->id_item) {
                // Надеваемый предмет из инвентаря
                $ivent_item = $this->pdo->fetch('SELECT * FROM `ivent` WHERE `id` = ? AND `user_id` = ?', array($this->id_item, $this->user['id']));
                // Все надетые предметы игрока
                $nadeto_items = $this->pdo->fetch('SELECT * FROM `nadeto` WHERE `user_id` = ?', array($this->user['id']));

                if ($ivent_item['item'] == $nadeto_items[ $this->type($ivent_item['type']) ]) {
                    $this->message = '<div class=\'flex j-c ai-c\'>На вас уже надет данный предмет</div>';
                    $this->answer('mess', 0);
                } else {
                    // Было ли надето на игрока вообще что то
                    if ($nadeto_items[ $this->type($ivent_item['type']) ] > 0) {
                        // Ищем в инвентаре снимаемый предмет с игрока
                        $nadeto_item_ivent = $this->pdo->fetch('SELECT * FROM `ivent` WHERE `type` = ? AND `item` = ? AND `user_id` = ?',array($ivent_item['type'], $nadeto_items[ $this->type($ivent_item['type']) ], $this->user['id']));
                        if ($nadeto_item_ivent) {
                            // Если предмет найден, то просто увеличиваем на единицу
                            $this->pdo->query('UPDATE `ivent` SET `colvo` = ? WHERE `id` = ?', array(($nadeto_item_ivent['colvo'] + 1), $nadeto_item_ivent['id']));
                        } else {
                            // Если нет, то создаем новую запись для предмета
                            $this->pdo->query('INSERT INTO ivent (item, type, colvo, user_id) VALUES (?, ?, ?, ?)', array($nadeto_items[ $this->type($ivent_item['type']) ], $ivent_item['type'], 1, $this->user['id']));
                        }
                    }

                    // надеваем предмет на игрока
                    $this->pdo->query('UPDATE `nadeto` SET `'.$this->type($ivent_item['type']).'` = ? WHERE `user_id` = ?', array($ivent_item['item'], $this->user['id']));
                    // надеваемый предмет вычитаем из инвентаря
                    $this->pdo->query('UPDATE `ivent` SET `colvo` = ? WHERE `id` = ?', array(($ivent_item['colvo'] - 1), $ivent_item['id']));
                }

                if ($this->from == 'item' && $ivent_item['colvo'] == 1) {
                    $this->answer('page', '/ivent');
                } else $this->answer('reload', 0);
            }

        }

        public function craft() {

            // Проверка соответсвия
            if ($this->crafts[ $this->id ]['item'] == $this->item && $this->crafts[ $this->id ]['type'] == $this->type) {
                if ($this->crafts[ $this->id ]['craft_lvl'] <= $this->user['craft_lvl']) {

                    $all_items   = count( $this->crafts[ $this->id ]['craft_items'] );
                    $all_exist   = 0;
                    $items       = array();
                    $items_colvo = array();
                    // Проверка на соответсвие предметов для крафта
                    foreach ($this->crafts[ $this->id ]['craft_items'] as $ci) {
                        $item = $this->pdo->fetch('SELECT * FROM `ivent` WHERE `item` = ? AND `type` = ? AND `colvo` >= ? AND `user_id` = ?', array($ci['item'], $ci['type'], ($ci['colvo'] * $this->colvo), $this->user['id']));
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
                            $this->pdo->query('UPDATE ivent SET `colvo` = ? WHERE `item` = ? AND `type` = ? AND `user_id` = ?', array(($items[$i]['colvo'] - $items_colvo[ $i ]), $items[$i]['item'], $items[$i]['type'], $this->user['id']));
                        }
                        // Добавляем создаваемый предмет в инвентарь
                        $item = $this->pdo->fetch('SELECT * FROM `ivent` WHERE `item` = ? AND `type` = ? AND `user_id` = ?', array($this->item, $this->type, $this->user['id']));
                        if ($item) {
                            $this->pdo->query('UPDATE ivent SET `colvo` = ? WHERE `item` = ? AND `type` = ? AND `user_id` = ?', array(($item['colvo'] + $this->colvo), $this->item, $this->type, $this->user['id']));
                        } else {
                            $this->pdo->query('INSERT INTO ivent (item, type, colvo, user_id) VALUES (?, ?, ?, ?)', array($this->item, $this->type, $this->colvo, $this->user['id']));
                        }

                        $this->message = '<div class=\'flex j-c ai-c\'>Предмет успешно создан!</div>';
                        $this->answer('mess', 0);
                    } else {
                        $this->message = '<div class=\'flex j-c ai-c\'>Недостаточно ресурсов</div>';
                        $this->answer('mess', 0);
                    }

                }
            }

        }

        public function read() {

            if ($this->id_item) {

                $item_ivent = $this->pdo->fetch('SELECT * FROM `ivent` WHERE `id` = ? AND `user_id` = ?', array($this->id_item, $this->user['id']));
                if ($item_ivent) {
                    $item = $this->items[ $item_ivent['type'] ][ $item_ivent['item'] ];
                    if ($item['craft_lvl'] < $this->user['craft_lvl']) {
                        $this->message = '<div class=\'flex j-c ai-c\'>Вы уже открыли данный уровень!</div>';
                        $this->answer('mess', 0);
                    } else if ($item['craft_lvl'] !== ($this->user['craft_lvl'] + 1) && $item['craft_lvl'] !== $this->user['craft_lvl']) {
                        $this->message = '<div class=\'flex j-c ai-c\'>Откройте предыдущий ур. крафта</div>';
                        $this->answer('mess', 0);
                    } else {
                        $this->pdo->query('UPDATE ivent SET `colvo` = ? WHERE `id` = ?', array(($item_ivent['colvo'] - 1), $item_ivent['id']));
                        $this->pdo->query('UPDATE users SET `craft_lvl` = ? WHERE `id` = ?', array($item['craft_lvl'], $this->user['id']));

                        if ($this->from == 'item' && $item_ivent['colvo'] == 1) {
                            $this->answer('page', '/ivent');
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

        public function up_refuge() {

            $refuge = $this->pdo->fetch('SELECT * FROM `refuge` WHERE `user_id` = ?', array($this->user['id']));

            if ($this->refuges[ $refuge['lvl'] + 1 ]) {
                $all_items = count($this->refuges[ $refuge['lvl'] + 1 ]['craft_items']);
                $all_exist = 0;
                $items       = array();
                $items_colvo = array();

                // Проверка на соответсвие предметов
                foreach ($this->refuges[ $refuge['lvl'] + 1 ]['craft_items'] as $ci) {
                    $item = $this->pdo->fetch('SELECT * FROM `ivent` WHERE `item` = ? AND `type` = ? AND `colvo` >= ? AND `user_id` = ?', array($ci['item'], $ci['type'], $ci['colvo'], $this->user['id']));
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
                        $this->pdo->query('UPDATE ivent SET `colvo` = ? WHERE `item` = ? AND `type` = ? AND `user_id` = ?', array(($items[$i]['colvo'] - $items_colvo[ $i ]), $items[$i]['item'], $items[$i]['type'], $this->user['id']));
                    }
                    
                    // Повышаем уровень убежища
                    $this->pdo->query('UPDATE refuge SET `lvl` = ?, `hp` = ? WHERE `user_id` = ?', array($refuge['lvl'] + 1, $this->refuges[ $refuge['lvl'] + 1 ]['maxhp'], $this->user['id']));

                    $this->message = '<div class=\'flex j-c ai-c\'>Успешно!</div>';
                    $this->answer('messreload', 0);
                } else {
                    $this->message = '<div class=\'flex j-c ai-c\'>Недостаточно ресурсов</div>';
                    $this->answer('mess', 0);
                }
            } else {
                $this->message = '<div class=\'flex j-c ai-c\'>Максимальный уровень</div>';
                $this->answer('mess', 0);
            }

        }

        public function place() {

            if ($this->id_item) {
                $ivent_item = $this->pdo->fetch('SELECT * FROM `ivent` WHERE `id` = ? AND `user_id` = ?', array($this->id_item, $this->user['id']));
                $item       = $this->items[ $ivent_item['type'] ][ $ivent_item['item'] ];
                $refuge     = $this->pdo->fetch('SELECT * FROM `refuge` WHERE `user_id` = ?', array($this->user['id']));

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
                                if ($se['item'] == $ivent_item['item']) {
                                    $this->message = '<div class=\'flex j-c ai-c\'>Данный предмет уже помещен</div>';
                                    $this->answer('mess', 0);
                                }
                            }
                        }

                        // Помещаем предмет в слот
                        $find_slot = $this->pdo->fetch('SELECT * FROM `slots` WHERE `item` = 0 AND `type` = ? AND `user_id` = ?', array($item['reftype'], $this->user['id']));
                        if ($find_slot) {
                            $this->pdo->query('UPDATE slots SET `item` = ? WHERE `id` = ? AND `user_id` = ?', array($ivent_item['item'], $find_slot['id'], $this->user['id']));
                        } else {
                            $this->pdo->query('INSERT INTO slots (item, type, user_id) VALUES (?, ?, ?)', array($ivent_item['item'], $item['reftype'], $this->user['id']));
                        }

                        // Вычитаем помещаемый предмет из инвентаря
                        $this->pdo->query('UPDATE ivent SET `colvo` = ? WHERE `id` = ? AND `user_id` = ?', array(($ivent_item['colvo'] - 1), $ivent_item['id'], $this->user['id']));

                        if ($ivent_item['colvo'] == 1) {
                            $this->answer('page', '/ivent');
                        } else $this->answer('reload', 0);
                    } else {
                        $this->message = '<div class=\'flex j-c ai-c\'>Все слоты заняты</div>';
                        $this->answer('mess', 0);
                    }
                } else {
                    $this->message = '<div class=\'flex j-c ai-c\'>Улучшите убежище</div>';
                    $this->answer('mess', 0);
                }
            }

        }

        public function formation_answer() {

            if ($this->weather_mess) $this->message .= $this->weather_mess;
            if ($this->temp_mess) $this->message .= $this->temp_mess;
            if ($this->hp_mess) $this->message .= $this->hp_mess;

            return true;

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
                case 'messreload':
                    exit( json_encode( ['reload' => true, 'message' => $this->message, 'popup' => true] ) );
                    break;
            }

        }

    	public function main() {
            
            $this->from = htmlspecialchars( trim( $_POST['from'] ) );

            switch( $this->action ) {
                case 'srchloc': 
                    $this->srch_loc();
                    break;
                case 'srchlut':
                    $this->srch_lut();
                break;
                case 'eat':
                    $this->id_item = htmlspecialchars( intval( $_POST['id_item'] ) );
                    $this->eat();
                    break;
                case 'drink':
                    $this->id_item = htmlspecialchars( intval( $_POST['id_item'] ) );
                    $this->drink();
                    break;
                case 'sleep':
                    $this->hours = htmlspecialchars( intval( $_POST['hours'] ) );
                    $this->sleep();
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
                    $this->up_refuge();
                    break;
                case 'place':
                    $this->id_item = htmlspecialchars( intval( $_POST['id_item'] ) );
                    $this->place();
                    break;
            }

    	}
        
    }

    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        if ($_SESSION['token'] == $_POST['token'] || $_POST['token'] == 0 || $_SESSION['token'] == 0) {
            $GameActions = new GameActions($pdo, $game_items, $game_locs, $game_action_times, $game_crafts, $game_weathers, $game_temps, $game_refuges, $Sys->get_user(), $_GET['action']);
            $GameActions->main();
        }
    } else { exit('Hi!'); }