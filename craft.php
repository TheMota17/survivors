<?php
    require ''.$_SERVER['DOCUMENT_ROOT'].'/core/sys.php';
    require ''.$_SERVER['DOCUMENT_ROOT'].'/core/gamedata.php';

    Class Craft {

        public function __construct($pdo, $crafts, $user, $id, $item, $type)
        {

            $this->pdo = $pdo;

            $this->crafts = $crafts;

            $this->user = $user;

            $this->id   = htmlspecialchars( intval( $id ) );
            $this->item = htmlspecialchars( intval( $item ) );
            $this->type = htmlspecialchars( intval( $type ) );

        }

        public function get_id() {

            return $this->id;

        }

        public function get_item() {

            return $this->item;

        }

        public function get_type() {

            return $this->type;

        }

        public function get_type_name() {
            $types = [0 => 'Все', 1 => 'Разное', 2 => 'Шлемы', 3 => 'Броня', 4 => 'Оружие', 5 => 'Убежище'];

            return $types[ $this->type ];
        }


        public function set_type() {

            if (! $this->type || $this->type == 0) $this->type = 2; // Тип шлем

        }

        public function verif_item() {

            if ($this->crafts[ $this->id ]['item'] == $this->item && $this->crafts[ $this->id ]['type'] == $this->type && $this->crafts[ $this->id ]['craft_lvl'] <= $this->user['craft_lvl']) return true;

        }

        public function exit( $move ) {

            switch( $move) {
                case 'empty':
                    exit('
                        <div class=\'flex j-c mt10\'>
                            <a href=\'/craft\' class=\'ajax\'>Предмет не найден</a>
                        </div>
                    ');
                break;
            }

        }

        public function main() {

            if ($this->item && $this->type) {
                if (! $this->verif_item() ) {
                    $this->exit( 'empty' );
                }
            } else if (!$this->type && !$this->id && !$this->item) {
                $this->set_type();
            }

        }
    }

    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        $Craft = new Craft($pdo, $game_crafts, $Sys->get_user(), $_GET['id'], $_GET['item'], $_GET['type']);
        $Craft->main();
    } else { exit('Hi!'); }

    require ''.$_SERVER['DOCUMENT_ROOT'].'/core/modules/tablo.php';
    require ''.$_SERVER['DOCUMENT_ROOT'].'/core/modules/menu.php';
?>

<div class='flex j-c ai-c fl-di-co mt10'>
    <div class='flex j-c'>
        Доступные предметы
    </div>
    <div class='craft-items backgr2 flex j-c ai-c fl-di-co mt5 pt5 pb5'>

        <? if ( $Craft->get_item() ) : ?>
            <div class='craft-item backgr1 flex j-c fl-di-co mt5 mb5 pt5 pb5'>
                <div class='craft-first-info flex'>
                    <div class='<?=$game_rares[ $game_items[ $Craft->get_type() ][ $Craft->get_item() ]['rare'] ]['border']?> flex j-c ai-c ml5'>
                        <img src='<?=$game_items[ $Craft->get_type() ][ $Craft->get_item() ]['img']?>'>
                    </div>
                    <div class='flex j-s fl-di-co'>
                        <div class='item-name fnt13 ml5 flex j-s'>
                            <?=$game_items[ $Craft->get_type() ][ $Craft->get_item() ]['nm']?>
                        </div>
                        <div class='item-rare fnt13 ml5 flex j-s'>
                            <span class='<?=$game_rares[ $game_items[ $Craft->get_type() ][ $Craft->get_item() ]['rare'] ]['class']?>'>
                                <?=$game_rares[ $game_items[ $Craft->get_type() ][ $Craft->get_item() ]['rare'] ]['word']?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class='craft-info backgr2 flex j-c fl-di-co ml5 mr5 mt5'>
                    <div class='flex j-c mt10'>
                        <div class='wdth96 flex j-c'>
                            <hr class='hr-style mr5'> Инфо <hr class='hr-style ml5'>
                        </div>
                    </div>
                    <div class='flex j-c ai-c fl-di-co mt5'>
                        <div class='iteminfo-div'>
                            <span class='ml5'>Тип: <?=$game_items[ $Craft->get_type() ][ $Craft->get_item() ]['type']?></span>
                        </div>
                        <? if ($items_pred[ $Craft->get_type() ][ $Craft->get_item() ]) : ?>
                            <div class='iteminfo-div mt5'>
                                <span class='ml5'>
                                    <img src='/img/icons/info.png' />
                                    <?=$items_pred[ $Craft->get_type() ][ $Craft->get_item() ]?>
                                </span>
                            </div>
                        <? else : ?>
                            <div class='iteminfo-div mt5'>
                                <span class='ml5'>
                                    <img src='/img/icons/info.png' />
                                    <? switch($game_items[ $Craft->get_type() ][ $Craft->get_item() ]['type']) :
                                        case 'Пища' : ?>
                                            Ну пищу едят как-бы)
                                        <? break; ?>
                                        <? case 'Материал' : ?>
                                            Используется для крафта
                                        <? break; ?>
                                        <? case 'Боеприпас' : ?>
                                            Используется для оружия
                                        <? break; ?>
                                        <? case 'Книга' : ?>
                                            Повышает уровень крафта
                                        <? break; ?>
                                        <? case 'Шлем' : ?>
                                            Повышает защиту от крит. удара
                                        <? break; ?>
                                        <? case 'Броня' : ?>
                                            Защищает от входящего урона
                                        <? break; ?>
                                        <? case 'Оружие' : ?>
                                            Средство обороны
                                        <? break; ?>
                                    <? endswitch; ?>
                                </span>
                            </div>
                        <? endif; ?>

                        <? if ($game_items[ $Craft->get_type() ][ $Craft->get_item() ]['eff']) : ?>
                            <? foreach($game_items[ $Craft->get_type() ][ $Craft->get_item() ]['eff'] as $key => $eff) : ?>
                                <? switch($key) :
                                    case 'hung': ?>
                                        <div class='iteminfo-div mt5'>
                                            <span class='ml5'>
                                                <img src='/img/icons/hung.png' />
                                                Голод: -<?=$eff?>
                                            </span>
                                        </div>
                                    <? break; ?>
                                    <? case 'thirst': ?>
                                        <div class='iteminfo-div mt5'>
                                            <span class='ml5'>
                                                <img src='/img/icons/thirst.png' />
                                                Жажда: -<?=$eff?>
                                            </span>
                                        </div>
                                    <? break; ?>
                                    <? case 'hp': ?>
                                        <div class='iteminfo-div mt5'>
                                            <span class='ml5'>
                                                <img src='/img/icons/hp.png' />
                                                Здоровье: +<?=$eff?>
                                            </span>
                                        </div>
                                    <? break; ?>
                                <? endswitch; ?>
                            <? endforeach; ?>
                        <? endif; ?>

                        <? if ($game_items[ $Craft->get_type() ][ $Craft->get_item() ]['dmgabs']) : ?>
                            <div class='iteminfo-div flex j-sb mt5'>
                                <div class='ml5'>
                                    <img src='/img/icons/abs.png' class='item14-1' /> Подавление урона: -<?=$game_items[ $Craft->get_type() ][ $Craft->get_item() ][ 'dmgabs' ]?>
                                </div>
                            </div>
                        <? elseif($game_items[ $Craft->get_type() ][ $Craft->get_item() ]['dmgmin']) : ?>
                            <div class='iteminfo-div flex j-sb mt5'>
                                <div class='ml5'>
                                    <img src='/img/icons/dmg.png' class='item14-1' /> Урон:
                                    <?=$game_items[ $Craft->get_type() ][ $Craft->get_item() ][ 'dmgmin' ]?>
                                    -
                                    <?=$game_items[ $Craft->get_type() ][ $Craft->get_item() ][ 'dmgmax' ]?>
                                </div>
                            </div>
                        <? endif; ?>

                        <? if ($game_items[ $Craft->get_type() ][ $Craft->get_item() ][ 'power' ]) : ?>
                            <div class='iteminfo-div flex j-sb mt5'>
                                <div class='ml5'>
                                    <img src='/img/icons/power.png' class='item14-1' /> Бонус к мощи: <?=$game_items[ $Craft->get_type() ][ $Craft->get_item() ][ 'power' ]?>
                                </div>
                            </div>
                        <? endif; ?>
                    </div>
                    <div class='flex j-c mt10'>
                        <div class='wdth96 flex j-с'>
                            <hr class='hr-style mr5'> Компоненты <hr class='hr-style ml5'>
                        </div>
                    </div>
                    <div class='flex j-c mt5'>
                        <div class='wdth96 flex j-s'>
                            <? $all_ci  = count($game_crafts[ $Craft->get_id() ]['craft_items']); ?>
                            <? $ci_iter = 1; ?>
                            <? foreach($game_crafts[ $Craft->get_id() ]['craft_items'] as $ci) : ?>
                                <div class='flex j-c ai-c fl-di-co'>
                                    <div class='<?=$game_rares[ $game_items[ $ci['type'] ][ $ci['item'] ]['rare'] ]['border']?>'>
                                        <a href='/item?item=<?=$ci['item']?>&type=<?=$ci['type']?>&view=1' class='ajax'>
                                            <img src='<?=$game_items[ $ci['type'] ][ $ci['item'] ]['img']?>' />
                                        </a>
                                    </div>
                                    <div class='item-colvo backgr1 flex j-c ai-c'>
                                        <?=$ci['colvo']?>
                                    </div>
                                </div>

                                <? if ($ci_iter < $all_ci) : ?>
                                    <div class='flex j-c ai-c ml5 mr5'>
                                        +
                                    </div>
                                <? endif; ?>

                                <? $ci_iter += 1; ?>
                            <? endforeach; ?>
                        </div>
                    </div>
                    <? if ($game_crafts[ $Craft->get_id() ]['tools']) : ?>
                        <div class='flex j-c mt10'>
                            <div class='wdth96 flex j-с'>
                                <hr class='hr-style mr5'> Инструменты <hr class='hr-style ml5'>
                            </div>
                        </div>
                        <div class='flex j-c mt5'>
                            <div class='wdth96 flex j-s'>
                                <? $all_tools  = count($game_crafts[ $Craft->get_id() ]['tools']); ?>
                                <? $t_iter = 1; ?>
                                <? foreach($game_crafts[ $Craft->get_id() ]['tools'] as $t) : ?>
                                    <div class='flex j-c ai-c fl-di-co'>
                                        <div class='<?=$game_rares[ $game_items[ $t['type'] ][ $t['item'] ]['rare'] ]['border']?>'>
                                            <a href='/item?item=<?=$t['item']?>&type=<?=$t['type']?>&view=1' class='ajax'>
                                                <img src='<?=$game_items[ $t['type'] ][ $t['item'] ]['img']?>' />
                                            </a>
                                        </div>
                                    </div>
                                    <? if ($t_iter < $all_tools) : ?>
                                        <div class='flex j-c ai-c ml5 mr5'>
                                            +
                                        </div>
                                    <? endif; ?>
                                    <? $t_iter += 1; ?>
                                <? endforeach; ?>
                            </div>
                        </div>
                    <? endif; ?>
                    <? if ($game_items[ $Craft->get_type() ][ $Craft->get_item() ]['ammu']) : ?>
                        <div class='flex j-c mt10'>
                            <div class='wdth96 flex j-с'>
                                <hr class='hr-style mr5'> Боеприпасы <hr class='hr-style ml5'>
                            </div>
                        </div>
                        <div class='flex j-c mt5'>
                            <div class='wdth96 flex j-s'>
                                <? foreach($game_items[ $Craft->get_type() ][ $Craft->get_item() ]['ammu'] as $ammu) : ?>
                                    <div class='flex j-c ai-c fl-di-co mr5'>
                                        <div class='<?=$game_rares[ $game_items[ $ammu['t'] ][ $ammu['i'] ]['rare'] ]['border']?>'>
                                            <a href='/item?item=<?=$ammu['i']?>&type=<?=$ammu['t']?>&view=1' class='ajax'>
                                                <img src='<?=$game_items[ $ammu['t'] ][ $ammu['i'] ]['img']?>' />
                                            </a>
                                        </div>
                                    </div>
                                <? endforeach; ?>
                            </div>
                        </div>
                    <? endif; ?>
                    <div class='flex j-c mt10'>
                        <div class='wdth96 flex j-s'>
                            <hr class='hr-style mr5'> Количество <hr class='hr-style ml5'>
                        </div>
                    </div>
                    <div class='flex j-c mt5 mb5'>
                        <div class='item-colvo backgr1 color3 flex j-c ai-c' id='colvo_craft'>1</div>
                    </div>
                    <div class='flex j-c mb5'>
                        <input class='colvo-range-input mt10' id='colvo_range_input' type='range' min='1' max='50' step='1' value='1'>
                    </div>
                    <div class='flex j-c mt5 mb5'>
                        <button class='move-btn craft-item-btn relative mt5 flex j-s ai-c' id='craft'>
                            <div class='game-btn-icon ml5 mr5 flex j-c ai-c'>
                                <img src='/img/icons/menu/craft.png' />
                            </div>
                            <div class='flex j-c ai-c' id='txt_craft'>Создать</div>
                            <div class='game-btn-bar' id='bar_craft'></div>
                        </button>
                    </div>
                </div>
            </div>

        <? else: ?>
            <div class='wdth96 flex j-s'>
                <button class='sort-btn flex j-c ai-c ml5' id='craft_sort_btn'>
                    <img src='/img/icons/sort.png' class='mr5' id='craft_sort_btn' /><?=$Craft->get_type_name()?>
                </button>
            </div>
            <div class='none craft-sort-menu flex j-s ai-c mt5' id='craft_sort_menu'>
                <a href='/craft?type=2' class='ajax flex j-c ai-c wdth100 mr5'>Шлемы</a>
                <a href='/craft?type=3' class='ajax flex j-c ai-c wdth100 mr5'>Броня</a>
                <a href='/craft?type=4' class='ajax flex j-c ai-c wdth100 mr5'>Оружие</a>
                <a href='/craft?type=5' class='ajax flex j-c ai-c wdth100 mr5'>Убежище</a>
                <a href='/craft?type=1' class='ajax flex j-c ai-c wdth100'>Разное</a>
            </div>

            <? $iter = 0; ?>
            <? foreach ($game_crafts as $gc) : ?>
                <? if ($gc['craft_lvl'] <= $Sys->user_info('userinfo', 'craft_lvl')) : ?>
                    <? if ($Craft->get_type() == $gc['type']) : ?>
                        <div class='craft-item backgr1 flex j-c fl-di-co mt5 mb5 pt5 pb5'>
                            <div class='craft-first-info flex'>
                                <div class='<?=$game_rares[ $game_items[ $gc['type'] ][ $gc['item'] ]['rare'] ]['border']?> flex j-c ai-c ml5'>
                                    <img src='<?=$game_items[ $gc['type'] ][ $gc['item'] ]['img']?>'>
                                </div>
                                <div class='flex j-s fl-di-co'>
                                    <div class='item-name fnt13 ml5 flex j-s'>
                                        <?=$game_items[ $gc['type'] ][ $gc['item'] ]['nm']?>
                                    </div>
                                    <div class='item-rare fnt13 ml5 flex j-s'>
                                        <span class='<?=$game_rares[ $game_items[ $gc['type'] ][ $gc['item'] ]['rare'] ]['class']?>'>
                                            <?=$game_rares[ $game_items[ $gc['type'] ][ $gc['item'] ]['rare'] ]['word']?>
                                        </span>
                                    </div>
                                </div>
                                <div class='ml5 mr5 fl1 flex j-e'>
                                    <a class='ajax craft-item-info flex j-c ai-c' href='/craft?id=<?=$iter?>&item=<?=$gc['item']?>&type=<?=$gc['type']?>'>
                                        <img src='/img/icons/menu/craft.png'/>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <? endif; ?>
                <? endif; ?>
                
                <? $iter += 1; ?>
            <? endforeach; ?>
        <? endif; ?>

    </div>
</div>