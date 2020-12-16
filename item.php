<?php
    require ''.$_SERVER['DOCUMENT_ROOT'].'/core/sys.php';
    require ''.$_SERVER['DOCUMENT_ROOT'].'/core/gamedata.php';

    Class Item {

    	public function __construct($pdo, $user, $item, $type, $view, $id)
        {
        	
            $this->pdo = $pdo;

            $this->user    = $user;

            $this->item    = htmlspecialchars( intval( $item ) );
            $this->type    = htmlspecialchars( intval( $type ) );
            $this->view    = htmlspecialchars( trim( $view ) );
            $this->id_item = htmlspecialchars( intval( $id ) );

    	}

    	public function all_exist( $elem ) {

            switch( $elem ) {
                case 'id':
                    if ( $this->id_item ) return true;
                    break;
                case 'item':
                    if ($this->item && $this->type) return true;
                break;
            }

    	}

        public function get_game_item() {

            return $this->game_item;

        }

        public function get_ivent_item() {

            return $this->ivent_item;

        }

        public function get_view() {

            return $this->view;

        }

        public function get_nadeto() {

            return $this->user_nadeto;

        }

        public function game_item() {

            if ($this->all_exist('item')) {
                $this->game_item['item'] = $this->item;
                $this->game_item['type'] = $this->type;
            }

        }

        public function ivent_item() {

            if ($this->all_exist('id')) {
                $this->ivent_item = $this->pdo->fetch('SELECT * FROM `ivent` WHERE `id` = ? AND `colvo` > 0 AND `user_id` = ?', array(
                    $this->id_item, $this->user[ 'id' ]
                ));

                if (! $this->ivent_item) {
                    $this->message = '
                        <div class=\'flex j-c mt10\'>
                            <a href=\'/ivent\' class=\'ajax\'>Предмет не найден</a>
                        </div>
                    ';
                    $this->answer( 'exit' );
                }
            } else {
                $this->message = '
                    <div class=\'flex j-c mt10\'>
                        <a href=\'/ivent\' class=\'ajax\'>Предмет не найден</a>
                    </div>
                ';
                $this->answer( 'exit' );
            }

        }

        public function nadeto_items() {

            $this->user_nadeto = $this->pdo->fetch('SELECT * FROM `nadeto` WHERE `user_id` = ?', array( $this->user[ 'id' ] ));

        }

        public function answer( $ans ) {

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
            }

        }

    	public function main() {

            if ( $this->view ) {
                $this->game_item();
            } else {
                $this->ivent_item();
                $this->nadeto_items();
            }

    	}
    }

    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        $Item = new Item($pdo, $Sys->get_user(), $_GET['item'], $_GET['type'], $_GET['view'], $_GET['id']);
        $Item->main();
    } else { exit('Hi!'); }

    require ''.$_SERVER['DOCUMENT_ROOT'].'/core/modules/tablo.php';
    require ''.$_SERVER['DOCUMENT_ROOT'].'/core/modules/menu.php';
?>

<? 
    $item         = ($Item->get_view()) ? $Item->get_game_item() : $Item->get_ivent_item();
    $nadeto       = ($Item->get_view()) ? 0 : $Item->get_nadeto();
    $nadeto_elems = [0, 0, 'helm', 'arm', 'weap'];
?>

<div class='flex j-c ai-c fl-di-co mt10'>
    <div class='flex j-c'>
        Предмет
    </div>
    <div class='item-iteminfo backgr2 pt5 pb5 mt5'>
        <div class='flex ml5'>
            <div class='iteminfo-img flex j-s'>
                <div class='item32-1 flex j-c ai-c ml5'>
                    <div class='<?=$game_rares[ $game_items[ $item['type'] ][ $item['item'] ][ 'rare' ] ]['border']?> flex j-c ai-c'>
                        <img src='<?=$game_items[ $item['type'] ][ $item['item'] ][ 'img' ]?>' />
                    </div>
                </div>
            </div>
            <div class='flex fl-di-co'>
                <div class='iteminfo-name ml5'>
                    <?=$game_items[ $item['type'] ][ $item['item'] ][ 'nm' ]?>
                </div>
                <div class='iteminfo-rare ml5'>
                    <span class='<?=$game_rares[ $game_items[ $item['type'] ][ $item['item'] ][ 'rare' ] ]['class']?> fnt13'>
                        <?=$game_rares[ $game_items[ $item['type'] ][ $item['item'] ][ 'rare' ] ]['word']?>
                    </span>
                </div>
            </div>
        </div>
        <div class='flex j-c mt5'>
            <div class='wdth96 flex j-c'>
                <hr class='hr-style mr5'> Инфо <hr class='hr-style ml5'>
            </div>      
        </div>
        <div class='flex j-c ai-c mt5'>
            <div class='wdth96 flex j-c ai-c fl-di-co'>
                <div class='iteminfo-div'>
                    <span class='ml5'>Тип: <?=$game_items[ $item['type'] ][ $item['item'] ][ 'type' ]?></span>
                </div>

                <? if ($items_pred[ $item['type'] ][ $item['item'] ]) : ?>
                    <div class='iteminfo-div mt5'>
                        <span class='ml5'>
                            <img src='/img/icons/info.png' />
                            <?=$items_pred[ $item['type'] ][ $item['item'] ]?>
                        </span>
                    </div>
                <? else : ?>
                    <div class='iteminfo-div mt5'>
                        <span class='ml5'>
                            <img src='/img/icons/info.png' />
                            <? switch($game_items[ $item['type'] ][ $item['item'] ]['type']) :
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

                <? if ($game_items[ $item['type'] ][ $item['item'] ]['eff']) : ?>
                    <? foreach($game_items[ $item['type'] ][ $item['item'] ]['eff'] as $key => $eff) : ?>
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

                <? if ($game_items[ $item['type'] ][ $item['item'] ]['dmgabs']) : ?>
                    <div class='iteminfo-div flex j-sb mt5'>
                        <div class='ml5'>
                            <img src='/img/icons/abs.png' class='item14-1' /> Подавление урона: -<?=$game_items[ $item['type'] ][ $item['item'] ][ 'dmgabs' ]?>
                        </div>
                        <? if (! $Item->get_view()) : ?>
                            <div class='ml5'>
                                <? if ($game_items[ $item['type'] ][ $item['item'] ]['dmgabs'] > $game_items[ $item['type'] ][ $nadeto[ $nadeto_elems[$item['type']] ] ]['dmgabs']) : ?>
                                    <img src='/img/icons/better.png' class='item14-1 mr5' />
                                <? elseif ($game_items[ $item['type'] ][ $nadeto[ $nadeto_elems[$item['type']] ] ]['dmgabs'] == $game_items[ $item['type'] ][ $item['item'] ]['dmgabs']) : ?>
                                    <img src='/img/icons/equally.png' class='item14-1 mr5' />
                                <? else : ?>
                                    <img src='/img/icons/worse.png' class='item14-1 mr5' />
                                <? endif; ?>
                            </div>
                        <? endif; ?>
                    </div>
                <? elseif ($game_items[ $item['type'] ][ $item['item'] ]['dmgmin']): ?>
                    <div class='iteminfo-div flex j-sb mt5'>
                        <div class='ml5'>
                            <img src='/img/icons/dmg.png' class='item14-1' /> Урон:
                            <?=$game_items[ $item['type'] ][ $item['item'] ][ 'dmgmin' ]?>
                            -
                            <?=$game_items[ $item['type'] ][ $item['item'] ][ 'dmgmax' ]?>
                        </div>
                        <? if (! $Item->get_view()) : ?>
                            <div class='ml5'>
                                <? if ($game_items[ $item['type'] ][ $item['item'] ]['dmgmin'] > $game_items[ $item['type'] ][ $nadeto[ $nadeto_elems[$item['type']] ] ]['dmgmin']) : ?>
                                    <img src='/img/icons/better.png' class='item14-1 mr5' />
                                <? elseif ($game_items[ $item['type'] ][ $nadeto[ $nadeto_elems[$item['type']] ] ]['dmgmin'] == $game_items[ $item['type'] ][ $item['item'] ]['dmgmin']) : ?>
                                    <img src='/img/icons/equally.png' class='item14-1 mr5' />
                                <? else : ?>
                                    <img src='/img/icons/worse.png' class='item14-1 mr5' />
                                <? endif; ?>
                            </div>
                        <? endif; ?>
                    </div>
                <? endif; ?>

                <? if ($game_items[ $item['type'] ][ $item['item'] ][ 'power' ]) : ?>
                    <div class='iteminfo-div flex j-sb mt5'>
                        <div class='ml5'>
                            <img src='/img/icons/power.png' class='item14-1' /> Бонус к мощи: <?=$game_items[ $item['type'] ][ $item['item'] ][ 'power' ]?>
                        </div>
                        <? if (! $Item->get_view()) : ?>
                            <div class='ml5'>
                                <? if ($game_items[ $item['type'] ][ $item['item'] ]['power'] > $game_items[ $item['type'] ][ $nadeto[ $nadeto_elems[$item['type']] ] ]['power']) : ?>
                                    <img src='/img/icons/better.png' class='item14-1 mr5' />
                                <? elseif ($game_items[ $item['type'] ][ $nadeto[ $nadeto_elems[$item['type']] ] ]['power'] == $game_items[ $item['type'] ][ $item['item'] ]['power']) : ?>
                                    <img src='/img/icons/equally.png' class='item14-1 mr5' />
                                <? else : ?>
                                    <img src='/img/icons/worse.png' class='item14-1 mr5' />
                                <? endif; ?>
                            </div>
                        <? endif; ?>
                    </div>
                <? endif; ?>

                <? if (!$Item->get_view()) : ?>
                    <div class='iteminfo-div flex ai-c mt5'>
                        <span class='ml5'>
                            <img src='/img/icons/colvo.png' class='item14-1' /> Количество: <?=$item[ 'colvo' ]?>
                        </span>
                    </div>
                <? endif; ?>
            </div>
        </div>
        <? if ($game_items[ $item['type'] ][ $item['item'] ]['ammu']) : ?>
            <div class='flex j-c mt10'>
                <div class='wdth96 flex j-c'>
                    <hr class='hr-style mr5'> Боеприпасы <hr class='hr-style ml5'>
                </div>      
            </div>
            <div class='flex j-c ai-c pt5 pb5'>
                <div class='wdth96 flex j-c'>
                    <div class='wdth96 flex j-s'>
                        <? foreach($game_items[ $item['type'] ][ $item['item'] ]['ammu'] as $ammu) : ?>
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
            </div>
        <? endif; ?>
    </div>
</div>

<? if (! $Item->get_view()) : ?>
    <? switch($game_items[ $item['type'] ][ $item['item'] ]['move']) : 
        case 'nadet': ?>
            <div class='flex j-c mt10'>
                <div class='item-moves backgr2 flex j-c pt5 pb5'>
                    <button class='move-btn moves-btn relative' id='nadet'>
                        <span id='txt_nadet'>Надеть</span>
                        <div class='game-btn-bar' id='bar_nadet'></div>
                    </button>
                </div>
            </div>
        <? break; ?>
        <? case 'eat': ?>
            <div class='flex j-c mt10'>
                <div class='item-moves backgr2 flex j-c pt5 pb5'>
                    <button class='move-btn moves-btn relative' id='eat'>
                        <span id='txt_eat'>Использовать</span>
                        <div class='game-btn-bar' id='bar_eat'></div>
                    </button>
                </div>
            </div>
        <? break; ?>    
        <? case 'drink': ?>
            <div class='flex j-c mt10'>
                <div class='item-moves backgr2 flex j-c pt5 pb5'>
                    <button class='move-btn moves-btn relative' id='drink'>
                        <span id='txt_drink'>Использовать</span>
                        <div class='game-btn-bar' id='bar_drink'></div>
                    </button>
                </div>
            </div>
        <? break; ?>
        <? case 'read': ?>
            <div class='flex j-c mt10'>
                <div class='item-moves backgr2 flex j-c pt5 pb5'>
                    <button class='move-btn moves-btn relative' id='read'>
                        <span id='txt_read'>Читать</span>
                        <div class='game-btn-bar' id='bar_read'></div>
                    </button>
                </div>
            </div>
        <? break; ?>
        <? case 'place': ?>
            <div class='flex j-c mt10'>
                <div class='item-moves backgr2 flex j-c pt5 pb5'>
                    <button class='move-btn moves-btn relative' id='place'>
                        <span id='txt_place'>Поместить</span>
                        <div class='game-btn-bar' id='bar_place'></div>
                    </button>
                </div>
            </div>
        <? break; ?>
    <? endswitch; ?>
<? endif; ?>

<? if ($item['type'] !== 1 && $item['type'] !== 5 && !$Item->get_view()) : ?>
    <? if ($nadeto[ $nadeto_elems[$item['type']] ] > 0) : ?>
        <div class='flex j-c ai-c fl-di-co mt10'>
            <div class='flex j-c'>
                На вас надето
            </div>
            <div class='item-iteminfo backgr2 pt5 pb5 mt5'>
                <div class='flex ml5'>
                    <div class='iteminfo-img flex j-s'>
                        <div class='item32-1 flex j-c ai-c ml5'>
                            <div class='<?=$game_rares[ $game_items[ $item['type'] ][ $nadeto[$nadeto_elems[$item['type']]] ][ 'rare' ] ]['border']?> flex j-c ai-c'>
                                <img src='<?=$game_items[ $item['type'] ][ $nadeto[ $nadeto_elems[$item['type']] ] ][ 'img' ]?>' />
                            </div>
                        </div>
                    </div>
                    <div class='flex fl-di-co'>
                        <div class='iteminfo-name ml5'>
                            <?=$game_items[ $item['type'] ][ $nadeto[ $nadeto_elems[$item['type']] ] ][ 'nm' ]?>
                        </div>
                        <div class='iteminfo-rare ml5'>
                            <span class='<?=$game_rares[ $game_items[ $item['type'] ][ $nadeto[$nadeto_elems[$item['type']]] ][ 'rare' ] ]['class']?> fnt13'>
                                <?=$game_rares[ $game_items[ $item['type'] ][ $nadeto[$nadeto_elems[$item['type']]] ][ 'rare' ] ]['word']?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class='flex j-c mt10'>
                    <div class='wdth96 flex j-c'>
                        <hr class='hr-style mr5'> Инфо <hr class='hr-style ml5'>
                    </div>      
                </div>
                <div class='flex fl-di-co j-c ai-c mt5'>
                    <div class='iteminfo-div'>
                        <span class='ml5'>
                            Тип: 
                            <?=$game_items[ $item['type'] ][ $nadeto[ $nadeto_elems[$item['type']] ] ][ 'type' ]?>
                        </span>
                    </div>
                    <? if ($game_items[ $item['type'] ][ $item['item'] ]['dmgabs']) : ?>
                        <div class='iteminfo-div mt5'>
                            <span class='ml5'>
                                <img src='/img/icons/abs.png' class='item14-1' /> 
                                Подавление урона: -<?=$game_items[ $item['type'] ][ $nadeto[ $nadeto_elems[$item['type']] ] ][ 'dmgabs' ]?>
                            </span>
                        </div>
                    <? else : ?>
                        <div class='iteminfo-div mt5'>
                            <span class='ml5'>
                                <img src='/img/icons/dmg.png' class='item14-1' /> Урон:
                                <?=$game_items[ $item['type'] ][ $nadeto[ $nadeto_elems[$item['type']] ] ][ 'dmgmin' ]?> 
                                -
                                <?=$game_items[ $item['type'] ][ $nadeto[ $nadeto_elems[$item['type']] ] ][ 'dmgmax' ]?>
                            </span>
                        </div>
                    <? endif; ?>
                    <div class='iteminfo-div mt5'>
                        <span class='ml5'>
                            <img src='/img/icons/power.png' class='item14-1' /> Бонус к мощи: <?=$game_items[ $item['type'] ][ $nadeto[ $nadeto_elems[$item['type']] ] ][ 'power' ]?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    <? endif; ?>
<? endif; ?>