<?php
    require ''.$_SERVER['DOCUMENT_ROOT'].'/core/sys.php';
    require ''.$_SERVER['DOCUMENT_ROOT'].'/core/gamedata.php';

    Class Ivent {

    	public function __construct($pdo, $items, $user, $page) 
        {

            $this->pdo = $pdo;

    		$this->items = $items;

            $this->user        = $user;
            $this->user_ivent  = array();
            $this->user_nadeto = array();

            $this->page = htmlspecialchars( intval( $page ) );

    	}

        public function init_item_pages() {

            if (! isset( $this->page ) || $this->page == 0) {
                $this->page = 1;
            }

            $limit            = 5;
            $offset           = $limit * ($this->page - 1);
            $all              = $this->pdo->rows('SELECT * FROM `ivent` WHERE `user_id` = ? AND `colvo` > 0', array( $this->user[ 'id' ] ));
            $this->user_cells = $all;
            $maxpage          = intval($all / $limit);

            if ($all > 5) {
                if ($this->page != 1) $this->pervpage = '<a href=\'/ivent?page='.($this->page - 1).'\' class=\'ajax nav-btn\'> ◄ </a>';
                if ($this->page >= 1 && $this->page <= $maxpage && $all !== 10) $this->nextpage = '<a href=\'/ivent?page='.($this->page + 1).'\' class=\'ajax nav-btn\'> ► </a>';
            }

            $this->nadeto_items();
            $this->ivent_items($limit, $offset);

        }

        public function user_info($type, $data) {

        	switch( $type ) {
        		case 'userinfo':
        			return $this->user[ $data ];
        		break;
        		case 'dopinfo':
        			switch( $data ) {
        				case 'nadeto':
        					return $this->user_nadeto;
        				break;
        				case 'ivent':
        					return $this->user_ivent;
        				break;
        				case 'cells':
        					return $this->user_cells;
        				break;
        			}
        		break;
        	}

        }

        public function navInfo() {

            return [$this->pervpage, $this->page, $this->nextpage];
            
        }

        public function nadeto_items() {

            $this->user_nadeto = $this->pdo->fetch('SELECT * FROM `nadeto` WHERE `user_id` = ?', array( $this->user[ 'id' ] ));

        }

    	public function ivent_items($limit, $offset) {

            $this->user_ivent = $this->pdo->fetchAll('SELECT * FROM `ivent` WHERE `user_id` = ? AND `colvo` > 0 LIMIT ? OFFSET ?', array(
            	$this->user[ 'id' ], $limit, $offset
            ));

    	}

    	public function main() {

            $this->init_item_pages();
            
    	}
    }

    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        $Ivent = new Ivent($pdo, $game_items, $Sys->get_user(), $_GET['page']);
        $Ivent->main();   
    } else { exit('Hi!'); }

    require ''.$_SERVER['DOCUMENT_ROOT'].'/core/modules/tablo.php';
    require ''.$_SERVER['DOCUMENT_ROOT'].'/core/modules/menu.php';
?>

<div class='flex j-c mt10'>
	<div class='ivent-pers backgr2 flex j-c ai-c pt5 pb5'>
		
        <? $nadeto = $Ivent->user_info('dopinfo', 'nadeto'); ?>

		<div class='maneken relative flex j-c ai-c'>
            <div class='<?=$game_items[ 2 ][ $nadeto['helm'] ]['class']?>'></div>
            <div class='<?=$game_items[ 3 ][ $nadeto['arm'] ]['class']?>'></div>

            <? if ($nadeto['helm'] == 0) : ?>
			<div class='hair<?=$nadeto['hair']?>'></div>
            <? endif; ?>
			<div class='beard<?=$nadeto['beard']?>'></div>
			<div class='cloth<?=$nadeto['cloth']?>'></div>
			<div class='pants<?=$nadeto['pants']?>'></div>
			<div class='fwear<?=$nadeto['fwear']?>'></div>

			<img class='man' src='/img/man/man.png' />
			<div class='maneken-shadow'></div>
		</div>

		<div class='user-info ml10'>
			<div class='user-name flex j-c ai-c'>
				<span class='user-name' id='user_name'><?=$Ivent->user_info('userinfo', 'login')?></span>
                <img src='/img/icons/hp.png' class='ml5 item14-1' /> <?=$Ivent->user_info('userinfo', 'live')?>
			</div>

			<div class='user-abs flex j-s ai-c mt5'>
				<img src='/img/icons/abs.png' class='ml5 mr5' />
				<span id='user_abs'>
					<?=( $game_items[ 2 ][ $nadeto['helm'] ]['dmgabs'] + $game_items[ 3 ][ $nadeto['arm'] ]['dmgabs'] )?>
				</span>
			</div>
			<div class='user-dmg flex j-s ai-c mt5'>
				<img src='/img/icons/dmg.png' class='ml5 mr5' />
                <? if ($nadeto[ 'weap' ] > 0) : ?>
    				<span id='user_dmg_min'>
    					<?=$game_items[ 4 ][ $nadeto['weap'] ]['dmgmin']?>
    				</span> 
    				- 
    				<span id='user_dmg_max'>
    					<?=$game_items[ 4 ][ $nadeto['weap'] ]['dmgmax']?>
    				</span>
                <? else : ?>
                    <span>0</span>
                <? endif; ?>
			</div>
			<div class='user-power flex j-s ai-c mt5'>
				<img src='/img/icons/power.png' class='ml5 mr5' />
				<span id='user_power'>
				<?=( $game_items[ 2 ][ $nadeto['helm'] ]['power'] + $game_items[ 3 ][ $nadeto['arm'] ]['power'] + $game_items[ 4 ][ $nadeto['weap'] ]['power'] )?>
				</span>
			</div>

			<div class='user-hp-info flex j-c ai-c mt5'>
				<img src='/img/icons/hung.png' class='mr5' />
                <div class='ivent-hung-bar'>
                    <div class='hung-bar' id='hung_bar' style='width: <?=$Ivent->user_info('userinfo', 'hung')?>%'></div>
                </div>
			</div>
			<div class='user-hung-info flex j-c ai-c mt5'>
				<img src='/img/icons/thirst.png' class='mr5' />
                <div class='ivent-thirst-bar'>
                    <div class='thirst-bar' id='thirst_bar' style='width: <?=$Ivent->user_info('userinfo', 'thirst')?>%'></div>
                </div>
			</div>
			<div class='user-fatigue-info flex j-c ai-c mt5'>
				<img src='/img/icons/sleep.png' class='mr5' />
				<div class='ivent-fatigue-bar'>
					<div class='fatigue-bar' id='fatigue_bar' style='width: <?=$Ivent->user_info('userinfo', 'fatigue')?>%'></div>
				</div>
			</div>
		</div>

	</div>
</div>

<div class='flex j-c ai-c fl-di-co mt10'>
	<div class='mb5'>
		Надето
	</div>
	<div class='pers-nadeto-items backgr2 flex j-c ai-c fl-di-co'>

		<div class='nadeto-helm backgr1 flex j-c mt5' id='nadeto_helm'>
			<div class='flex j-c ai-c'>
				<div class='item32-1'>
                    <? if ($nadeto[ 'helm' ] > 0) : ?>
                        <div class='<?=$game_rares[ $game_items[ 2 ][ $nadeto['helm'] ][ 'rare' ] ]['border']?> flex j-c ai-c'>
                            <img src='<?=$game_items[ 2 ][ $nadeto['helm'] ][ 'img' ]?>' />
                        </div>
                    <? else : ?>
                        <img src='/img/icons/ivent-helm.png' />
                    <? endif; ?>
		        </div>
		        <div class='flex j-c fl-di-co'>
                    <? if ($nadeto[ 'helm' ] > 0) : ?>
    		            <div class='item-name fnt13 ml5 flex j-s'>
    		                <?=$game_items[ 2 ][ $nadeto['helm'] ][ 'nm' ]?>
    		            </div>
    		            <div class='item-rare fnt13 ml5 flex j-s'>
    		                <span class='<?=$game_rares[ $game_items[ 2 ][ $nadeto['helm'] ][ 'rare' ] ]['class']?>'>
    		                	<?=$game_rares[ $game_items[ 2 ][ $nadeto['helm'] ][ 'rare' ] ]['word']?>
    		                </span>
    		            </div>
                    <? else : ?>
                        <div class='bolder fnt13 ml5 flex j-s'>
                            Голова
                        </div>
                        <div class='item-name fnt13 ml5 flex j-s'>
                            Ничего не надето
                        </div>
                    <? endif; ?>
		        </div>
			</div>
            <div class='flex j-c fl-di-co fl1'>
                <? if ($nadeto[ 'helm' ] > 0) : ?>
                    <div class='item-dmg-abs flex j-e ai-c'>
                        <img src='/img/icons/abs.png' />
                        <span><?=$game_items[ 2 ][ $nadeto['helm'] ][ 'dmgabs' ]?></span>
                    </div>
                    <div class='helm-power flex j-e ai-c'>
                        <img src='/img/icons/power.png' />
                        <span><?=$game_items[ 2 ][ $nadeto['helm'] ][ 'power' ]?></span>
                    </div>
                <? endif; ?>
            </div>
		</div>

		<div class='nadeto-arm backgr1 flex j-c mt5' id='nadeto_arm'>
			<div class='flex j-c ai-c'>
				<div class='item32-1'>
                	<? if ($nadeto[ 'arm' ] > 0) : ?>
                        <div class='<?=$game_rares[ $game_items[ 3 ][ $nadeto['arm'] ][ 'rare' ] ]['border']?> flex j-c ai-c'>
                            <img src='<?=$game_items[ 3 ][ $nadeto['arm'] ][ 'img' ]?>' />
                        </div>
                    <? else : ?>
                        <img src='/img/icons/ivent-arm.png' />
                    <? endif; ?>
		        </div>
		        <div class='flex j-c fl-di-co'>
                    <? if ($nadeto[ 'arm' ] > 0) : ?>
    		            <div class='item-name fnt13 ml5 flex j-s'>
    		            	<?=$game_items[ 3 ][ $nadeto['arm'] ][ 'nm' ]?>
    		            </div>
    		            <div class='item-rare fnt13 ml5 flex j-s'>
    		                <span class='<?=$game_rares[ $game_items[ 3 ][ $nadeto['arm'] ][ 'rare' ] ]['class']?>'>
    		                	<?=$game_rares[ $game_items[ 3 ][ $nadeto['arm'] ][ 'rare' ] ]['word']?>
    		                </span>
    		            </div>
                    <? else : ?>
                        <div class='bolder fnt13 ml5 flex j-s'>
                            Тело
                        </div>
                        <div class='item-name fnt13 ml5 flex j-s'>
                            Ничего не надето
                        </div>
                    <? endif; ?>
		        </div>
			</div>
            <div class='flex j-c fl-di-co fl1'>
                <? if ($nadeto[ 'arm' ] > 0) : ?>
                    <div class='item-dmg-abs flex j-e ai-c'>
                        <img src='/img/icons/abs.png' />
                        <span><?=$game_items[ 3 ][ $nadeto['arm'] ][ 'dmgabs' ]?></span>
                    </div>
                    <div class='helm-power flex j-e ai-c'>
                        <img src='/img/icons/power.png' />
                        <span><?=$game_items[ 3 ][ $nadeto['arm'] ][ 'power' ]?></span>
                    </div>
                <? endif; ?>
            </div>
		</div>

		<div class='nadeto-weap backgr1 flex j-c mt5 mb5' id='nadeto_weap'>
			<div class='flex j-c ai-c'>
				<div class='item32-1'>
                	<? if ($nadeto[ 'weap' ] > 0) : ?>
                       <div class='<?=$game_rares[ $game_items[ 4 ][ $nadeto['weap'] ][ 'rare' ] ]['border']?> flex j-c ai-c'>
                            <img src='<?=$game_items[ 4 ][ $nadeto['weap'] ][ 'img' ]?>' />
                        </div>
                    <? else : ?>
                        <img src='/img/icons/ivent-weap.png' />
                    <? endif; ?>
	            </div>
	            <div class='flex j-c fl-di-co'>
                    <? if ($nadeto[ 'weap' ] > 0) : ?>
    	                <div class='item-name fnt13 ml5 flex j-s'>
    	                    <?=$game_items[ 4 ][ $nadeto['weap'] ][ 'nm' ]?>
    	                </div>
    	                <div class='item-rare fnt13 ml5 flex j-s'>
    	                    <span class='<?=$game_rares[ $game_items[ 4 ][ $nadeto['weap'] ][ 'rare' ] ]['class']?>'>
    	                    	<?=$game_rares[ $game_items[ 4 ][ $nadeto['weap'] ][ 'rare' ] ]['word']?>
    	                    </span>
    	                </div>
                    <? else : ?>
                        <div class='bolder fnt13 ml5 flex j-s'>
                            Оружие
                        </div>
                        <div class='item-name fnt13 ml5 flex j-s'>
                            Ничего не надето
                        </div>
                    <? endif; ?>
	            </div>
			</div>
            <div class='flex j-c fl-di-co fl1'>
                <? if ($nadeto[ 'weap' ] > 0) : ?>
                    <div class='item-dmg-abs flex j-e ai-c'>
                        <img src='/img/icons/dmg.png' />
                        <span><?=$game_items[ 4 ][ $nadeto['weap'] ][ 'dmgmin' ]?></span> - <span><?=$game_items[ 4 ][ $nadeto['weap'] ][ 'dmgmax' ]?></span>
                    </div>
                    <div class='helm-power flex j-e ai-c'>
                        <img src='/img/icons/power.png' />
                        <span><?=$game_items[ 4 ][ $nadeto['weap'] ][ 'power' ]?></span>
                    </div>
                <? endif; ?>
            </div>
		</div>

	</div>
</div>

<div class='flex j-c ai-c fl-di-co mt10'>
	<div class='mb5'>
		Инвентарь <span id='cells_quant'><?=$Ivent->user_info('dopinfo', 'cells')?></span>/50
	</div>
	<div class='ivent-items backgr2 flex j-c ai-c fl-di-co' id='ivent_items'>
        <? $ivent = $Ivent->user_info('dopinfo', 'ivent'); ?>

        <? if ( $ivent ) : ?>
    		<? foreach($ivent as $iv) : ?>
    			<a href='/item?id=<?=$iv['id']?>' class='ajax item-div backgr1 flex j-sb mt5 mb5 pt5 pb5'>
                    <div class='fl1 flex j-s ai-c'>
                        <div class='item32-2 flex j-c ai-c'>
                            <div class='<?=$game_rares[ $game_items[ $iv['type'] ][ $iv['item'] ][ 'rare' ] ]['border']?> flex j-c ai-c'>
                                <img src='<?=$game_items[ $iv[ 'type' ] ][ $iv[ 'item' ] ][ 'img' ]?>' />
                            </div>
                        </div>
                    </div>

                    <div class='fl2 flex j-c fl-di-co color3 ml5'>
                        <div class='ivent-item-name flex j-c'>
                            <?=$game_items[ $iv[ 'type' ] ][ $iv[ 'item' ] ][ 'nm' ]?>
                        </div>
                        <div class='flex j-c fnt13'>
                            Тип: <?=$game_items[ $iv[ 'type' ] ][ $iv[ 'item' ] ][ 'type' ]?>
                        </div>
                    </div>

                    <div class='fl1 flex j-e'>
                        <div class='item-colvo backgr2 flex j-c ai-c'>
                        <?=$iv[ 'colvo' ]?>
                        </div>
                    </div>
                </a>
    		<? endforeach; ?>
        <? else : ?>
            <div class='flex j-c pt5 pb5'>
                Пусто
            </div>
        <? endif; ?>
	</div>
	<div class='ivent-nav backgr2 flex j-c mt5 pt5 pb5' id='ivent_nav'>
		<? foreach($Ivent->navInfo() as $nav) : ?>

			<? if ( $nav ) : ?>
				<span class='nav-btn'>
					<?=$nav?>
				</span>
			<? endif; ?>

		<? endforeach; ?>
	</div>
</div>