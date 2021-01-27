<template>
	<div v-if='api'>
	    <div v-if='refuge[`lvl`] == 0' class='flex j-c ai-c mt10'>
	        <div class='refuge-no backgr2 flex j-c ai-c fl-di-co pb5'>
	            <div class='refuge-back flex j-c' style='background: url(<?=$game_locs[ 1 ][ 'img' ][ img($game_locs, $Sys->user_info('userinfo', 'user_weather')) ]?>) top/cover no-repeat; align-items: center;'>
	                <div class='refuge-no-mess backgr1 flex j-c ai-c'>
	                    <img src='/assets/icons/mess.png' class='mr5' />
	                    <span class='mess'>У вас нету убежища</span>
	                </div>
	            </div>
	            <div class='wdth96 flex j-c mt5'>
	                <hr class='hr-style mr5'> Компоненты <hr class='hr-style ml5'>
	            </div>
	            <div class='refuge-resource wdth86 backgr1 flex j-s ai-c mt5 mb5 pt5 pb5'>
	                <div class='flex j-c ai-c ml5'>
	                    <? $all_ci  = count($game_refuges[ 1 ]['craft_items']); ?>
	                    <? $ci_iter = 1; ?>
	                    <? foreach($game_refuges[ 1 ]['craft_items'] as $ci) : ?>
	                        <div class='flex j-c ai-c fl-di-co'>
	                            <div class='<?=$game_rares[ $game_items[ $ci['type'] ][ $ci['item'] ]['rare'] ]['border']?>'>
	                                <a href='/item?item=<?=$ci['item']?>&type=<?=$ci['type']?>&view=1' class='ajax'>
	                                    <img src='<?=$game_items[ $ci['type'] ][ $ci['item'] ]['img']?>' />
	                                </a>
	                            </div>
	                            <div class='item-colvo backgr2 flex j-c ai-c'>
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
	            <button class='move-btn refuge-up relative flex j-s ai-c mt5' id='uprefuge'>
	                <div class='game-btn-icon ml5 mr5 flex j-c ai-c'>
	                    <img src='/assets/icons/lvl.png' class='item14-1' />
	                </div>
	                <span id='txt_uprefuge'>Построить</span>
	                <div class='game-btn-bar' id='bar_uprefuge'></div>
	            </button>
	        </div>
	    </div>
	    <div v-else>
		    <div class='flex j-c ai-c mt10'>
		        <div class='refuge-info backgr2 flex j-c ai-c fl-di-co pt5 pb5'>
		            <div class='wdth96 flex j-c'>
		                <hr class='hr-style mr5'> Убежище <hr class='hr-style ml5'>
		            </div>
		            <div class='wdth96 flex j-c ai-c fl-di-co'>
		                <div class='iteminfo-div mt5'>
		                    <span class='ml5'><img src='/assets/icons/info.png' /> <?=$game_refuges[ $refuge['lvl'] ]['nm']?></span>
		                </div>
		                <div class='iteminfo-div mt5'>
		                    <span class='ml5'><img src='/assets/icons/lvl.png' class='item14-1' /> Уровень: <?=$refuge['lvl']?></span>
		                </div>
		                <div class='iteminfo-div mt5'>
		                    <span class='ml5'><img src='/assets/icons/abs.png' class='item14-1' /> Броня: <?=$game_refuges[ $refuge['lvl'] ]['dmgabs']?></span>
		                </div>
		            </div>
		            <div class='refuge-back relative flex j-c mt5' style='background: url(<?=$game_locs[ 1 ][ 'img' ][ img($game_locs, $Sys->user_info('userinfo', 'user_weather')) ]?>) top/cover no-repeat; align-items: flex-end;'>

		                <div class='refuge-hp-wrapper flex j-c'>
		                    <div class='refuge-hp-bar' style='width: <?=hpPercent($refuge['hp'], $game_refuges[ $refuge['lvl'] ]['maxhp'])?>%;'></div>
		                </div>
		                <div class='refuge-hp-colvo backgr1 flex j-c ai-c pl5 pr5 pb5 pt5'>
		                    <div class='flex j-c ai-c'>
		                        <img src='/assets/icons/hp.png' class='item14-1 mr5' /> <span><?=$refuge['hp']?> / <?=$game_refuges[ $refuge['lvl'] ]['maxhp']?></span>
		                    </div>
		                </div>
		                <img src='<?=$game_refuges[ $refuge['lvl'] ]['img']?>' class='refuge-img block <?=$game_refuges[ $refuge['lvl'] ]['class']?>' />
		            </div>

		            <div class='wdth96 flex j-c mt5'>
		                <hr class='hr-style mr5'> Компоненты <hr class='hr-style ml5'>
		            </div>
		            <div class='refuge-resource wdth86 backgr1 flex j-c ai-c mt5 mb5 pt5 pb5'>
		                <? if ($game_refuges[ $refuge['lvl'] + 1 ]) : ?>
		                    <div class='wdth100 flex j-s ai-c ml5'>
		                        <? $all_ci  = count($game_refuges[ $refuge['lvl'] + 1 ]['craft_items']); ?>
		                        <? $ci_iter = 1; ?>
		                        <? foreach($game_refuges[ $refuge['lvl'] + 1 ]['craft_items'] as $ci) : ?>
		                            <div class='flex j-c ai-c fl-di-co'>
		                                <div class='<?=$game_rares[ $game_items[ $ci['type'] ][ $ci['item'] ]['rare'] ]['border']?>'>
		                                    <a href='/item?item=<?=$ci['item']?>&type=<?=$ci['type']?>&view=1' class='ajax'>
		                                        <img src='<?=$game_items[ $ci['type'] ][ $ci['item'] ]['img']?>' />
		                                    </a>
		                                </div>
		                                <div class='item-colvo backgr2 flex j-c ai-c'>
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
		                <? else : ?>
		                    <div class='wdth100 flex j-c ai-c'>
		                        <span class='mess'>Максимальный уровень</span>
		                    </div>
		                <? endif; ?>
		            </div>

		            <div class='refuge-moves flex j-c ai-c fl-di-co mt5'>
		                <button class='move-btn refuge-up relative flex j-s ai-c' id='uprefuge'>
		                    <div class='game-btn-icon ml5 mr5 flex j-c ai-c'>
		                        <img src='/assets/icons/lvl.png' class='item14-1' />
		                    </div>
		                    <span id='txt_uprefuge'>Улучшить</span>
		                    <div class='game-btn-bar' id='bar_uprefuge'></div>
		                </button>
		                <? if ($Sys->user_info('userinfo', 'in_refuge')) : ?>
		                    <button class='move-btn refuge-enter relative flex j-s ai-c mt5' id='enterrefuge'>
		                        <div class='game-btn-icon ml5 mr5 flex j-c ai-c'>
		                            <img src='/assets/icons/getout.png' />
		                        </div>
		                        <span id='txt_enterrefuge'>Выйти</span>
		                        <div class='game-btn-bar' id='bar_enterrefuge'></div>
		                    </button>
		                <? else : ?>
		                    <button class='move-btn refuge-enter relative flex j-s ai-c mt5' id='enterrefuge'>
		                        <div class='game-btn-icon ml5 mr5 flex j-c ai-c'>
		                            <img src='/assets/icons/enter.png' />
		                        </div>
		                        <span id='txt_enterrefuge'>Войти</span>
		                        <div class='game-btn-bar' id='bar_enterrefuge'></div>
		                    </button>
		                <? endif; ?>
		            </div>
		        </div>
		    </div>

		    <div class='flex j-c ai-c fl-di-co mt10'>
		        <div class='refuge-protection-slots backgr2 flex j-c ai-c fl-di-co mt5 pt5 pb5'>
		            <div class='wdth96 flex j-c'>
		                <hr class='hr-style mr5'> Защита <hr class='hr-style ml5'>
		            </div>
		            <div class='wdth96 flex j-c ai-c fl-di-co'>
		                <? $prot_colvo = $game_refuges[ $refuge['lvl'] ]['prot']; ?>

		                <? if ($prot_colvo == 0) : ?>
		                    <div class='protection-slot backgr1 flex j-c mt5'>
		                        Улучшите убежище
		                    </div>
		                <? else : ?>
		                    <? for($i = 0; $i < $prot_colvo; $i++) : ?>
		                        <? $item = $game_items[ 5 ][ $prot[$i]['item'] ]; ?>
		                        <? $j    = $i + 1; ?>
		                        <div class='protection-slot backgr1 flex j-c mt5'>
		                            <div class='flex j-c ai-c'>
		                                <div class='item32-1'>
		                                    <? if ($item) : ?>
		                                        <div class='<?=$game_rares[ $item['rare'] ]['border']?> flex j-c ai-c'>
		                                            <img src='<?=$item['img']?>' />
		                                        </div>
		                                    <? else : ?>
		                                        <img src='/assets/icons/slot.png' />
		                                    <? endif; ?>
		                                </div>
		                                <div class='flex j-c fl-di-co'>
		                                    <? if ($item) : ?>
		                                        <div class='item-name ml5 flex j-s'>
		                                            <?=$item['nm']?>
		                                        </div>
		                                        <div class='item-rare ml5 flex j-s'>
		                                            <span class='<?=$game_rares[ $item['rare'] ]['class']?>'>
		                                                <?=$game_rares[ $item['rare'] ]['word']?>
		                                            </span>
		                                        </div>
		                                    <? else : ?>
		                                        <div class='ml5'>Пусто</div>
		                                    <? endif; ?>
		                                </div>
		                            </div>
		                            <div class='flex j-c fl-di-co fl1'>
		                                <div class='flex j-e ai-c'>
		                                    <? if ($item) : ?>

		                                    <? else : ?>
		                                        <a class='ajax refuge-slot-plus flex j-c ai-c' href='/ivent?type=5&page=1'>
		                                            +
		                                        </a>
		                                    <? endif; ?>
		                                </div>
		                            </div>
		                        </div>
		                    <? endfor; ?>
		                <? endif; ?>
		            </div>

		            <div class='wdth96 flex j-c mt5'>
		                <hr class='hr-style mr5'> Инструменты <hr class='hr-style ml5'>
		            </div>

		            <div class='wdth96 flex j-c ai-c fl-di-co'>
		                <? $tools_colvo = $game_refuges[ $refuge['lvl'] ]['tools']; ?>

		                <? if ($tools_colvo == 0) : ?>
		                    <div class='protection-slot backgr1 flex j-c mt5'>
		                        Улучшите убежище       
		                    </div>
		                <? else : ?>
		                    <? for($i = 0; $i < $tools_colvo; $i++) : ?>
		                        <? $item = $game_items[ 5 ][ $tools[$i]['item'] ]; ?>
		                        <? $j    = $i + 1; ?>
		                        <div class='protection-slot backgr1 flex j-c mt5'>
		                            <div class='flex j-c ai-c'>
		                                <div class='item32-1'>
		                                    <? if ($item) : ?>
		                                        <div class='<?=$game_rares[ $item['rare'] ]['border']?> flex j-c ai-c'>
		                                            <img src='<?=$item['img']?>' />
		                                        </div>
		                                    <? else : ?>
		                                        <img src='/assets/icons/slot.png' />
		                                    <? endif; ?>
		                                </div>
		                                <div class='flex j-c fl-di-co'>
		                                    <? if ($item) : ?>
		                                        <div class='item-name ml5 flex j-s'>
		                                            <?=$item['nm']?>
		                                        </div>
		                                        <div class='item-rare ml5 flex j-s'>
		                                            <span class='<?=$game_rares[ $item['rare'] ]['class']?>'>
		                                                <?=$game_rares[ $item['rare'] ]['word']?>
		                                            </span>
		                                        </div>
		                                    <? else : ?>
		                                        <div class='ml5'>Пусто</div>
		                                    <? endif; ?>
		                                </div>
		                            </div>
		                            <div class='flex j-c fl-di-co fl1'>
		                                <div class='flex j-e ai-c'>
		                                    <? if ($item) : ?>
		                                        <? switch($tools[ $i ]['item']) : 
		                                            case 1: ?>
		                                                
		                                            <? break; ?>
		                                            <? default: ?>

		                                            <? break; ?>
		                                        <? endswitch; ?>
		                                    <? else : ?>
		                                        <a class='ajax refuge-slot-plus flex j-c ai-c' href='/ivent?type=5&page=1'>
		                                            +
		                                        </a>
		                                    <? endif; ?>
		                                </div>
		                            </div>
		                        </div>
		                    <? endfor; ?>
		                <? endif; ?>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
</template>

<script>
module.exports = {
	name: 'Refuge',
	data: () => ({
		api: false,

		items: undefined,
		rares: undefined,
		refuges: undefined,
		refuge: undefined,
		user: undefined
	}),
	beforeMount() {
		let params = new FormData();
    	params.append('token', localStorage.getItem('token'));

		axios.post('/core/ajax/Api.php?page=refuge', params)
		.then((response) => {
			if (response.data.popup) {
				this.$root.popup.active = true;
				this.$root.popup.text   = response.data.message;
			} else if (response.data.page) {
				this.$router.push(response.data.page)
			} else {
				this.items   = response.data.items;
				this.rares   = response.data.rares;
				this.refuges = response.data.refuges;
				this.refuge  = response.data.refuge;
				this.user    = response.data.user;

				this.api = true;
			}
		})
		.catch((error) => {
			console.log(error)
		})
	},
	methods: {

	}
}
</script>