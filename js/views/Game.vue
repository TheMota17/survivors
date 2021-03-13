<template>
	<div v-if='api'>
		<div class='flex j-c mt5'>
		    <div class='game-meteoсharact backgr2 flex j-sa pt5 pb5'>
		        <div class='flex j-c ai-c'>
		            <img class='item14-1 mr5' src='/assets/icons/time.png'><span id='time'>0</span>
		        </div>
		        <div class='flex j-c ai-c'>
		            <img class='item14-1 mr5' src='/assets/icons/weather-1.png' id='weather_img'>
		            <span id='weather_name'>0</span>
		        </div>
		        <div class='flex j-c ai-c'>
		            <img class='item14-1 mr5' src='/assets/icons/temp.png'><span id='temp'>0</span>°
		        </div>
		    </div>
		</div>

		<div class='flex j-c ai-c fl-di-co mt5'>
		    <div class='game-location backgr2 pb5'>
		        <div class='flex j-c ai-c pt5 pb5 fnt15'>
		            <img src='/assets/icons/loc.png' class='item14-1 mr5' />
		            <div class='loc-name mr5' id='loc_name'>0</div>
		        </div>
	            <div class='flex j-c pb5 fnt12'>
	                <span class='mr5'>Исследовано </span><span id='loc_explored'>0</span>%
	            </div>

		        <div class='wdth100 relative flex j-c'>
		            <div class='game-canvas-loader flex j-c ai-c' id='game_canv_loader'>
		                <div class='flex j-c ai-c'>
		                    Загрузка...
		                </div>
		            </div>
		            <div class='game-canvas-fps'>
		                <div class='fps-body flex j-s ai-c'>
		                    <div class='flex'>
		                        FPS: <span class='ml5' id='fps'>0</span>
		                    </div>
		                </div>
		            </div>
		            <div class='game-canvas-btns flex j-e ai-e'>
		                <div class='game-canvas-btns-wdth flex j-c ai-c fl-di-co' id='game_btns'>
		                    <div class='flex j-c ai-c'>
	                            <button class='game-btn game-btn-big' id='up'></button>
		                    </div>
		                    <div class='flex j-c'>
		                        <button class='game-btn game-btn-big mr10' id='left'></button>
		                        <button class='game-btn game-btn-big ml10' id='right'></button>
		                    </div>
		                    <div class='flex j-c'>
	                            <button class='game-btn game-btn-big' id='down'></button>
		                    </div>
		                </div>
		            </div>
		            <canvas class='game-canvas flex j-c ai-c' id='game_canv' width='400' height='400'>Данная технология не поддерживается вашим браузером</canvas>
		        </div>

	            <div class='flex j-c mt5'>
	                <div class='loc-whatsrch'>
	                    <div class='mb5'>Можно найти:</div>
	                    <item-output :items='locs[ game.loc ][`srch_items`]' :sys-items='items' :sys-rares='rares'></item-output>
	                </div>
	            </div>

	            <div class='flex j-c mt10'>
	            	<button class='moves-btn flex j-s ai-c'>
		                <div class='game-btn-icon ml5 mr5 flex j-c ai-c'>
		                    <img src='/assets/icons/loc.png' />
		                </div>
		                <span>Искать другую локацию</span>
		            </button>
	            </div>

	            <sleep class='mt5'></sleep>
		    </div>
		</div>

		<surv></surv>
	</div>
</template>

<script>
let date = Date.now();
let ItemOutput = httpVueLoader('../components/ItemOutput.vue?_='+date)
let Sleep = httpVueLoader('../components/Sleep.vue?_='+date)
let Surv  = httpVueLoader('../components/Surv.vue?_='+date)

module.exports = {
	name: 'Game',
	data: () => ({
		api: false,
		game: undefined,
		items: undefined,
		rares: undefined,
		locs: undefined
	}),
	components: {
		ItemOutput, Sleep, Surv
	},
	beforeMount()
	{
		let params = new FormData();
    	params.append('token', localStorage.getItem('token'));

		axios.post('/core/Api/?page=game', params)
		.then((response) => {
			if (response.data.popup)
			{
				this.$root.popup.active = true;
				this.$root.popup.text   = response.data.message;
			} else if (response.data.page)
			{
				this.$router.push(response.data.page)
			} else
			{
				this.game  = response.data.game;
				this.items = response.data.items;
				this.rares = response.data.rares;
				this.locs  = response.data.locs;

				this.$root.tablo.hp = this.game.hp;
                this.$root.tablo.hung = this.game.hung;
                this.$root.tablo.thirst = this.game.thirst;
                this.$root.tablo.fatigue = this.game.fatigue;

				this.api = true;
			}
		})
		.catch((error) => {
			console.log(error)
		})
	}
}
</script>