<template>
	<div v-if='api'>
		<tablo :hp='game.hp' :hung='game.hung' :thirst='game.thirst' :fatigue='game.fatigue'></tablo>
	    <div v-if='refuge[`lvl`] == 0' class='flex j-c ai-c mt10'>
	        <div class='refuge-no backgr2 flex j-c ai-c fl-di-co pb5'>
	            <div class='refuge-back flex j-c ai-c' :style='{background: "url(" + locs[ 1 ][`img`] + ") no-repeat top/cover"}'>
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
	                    <div v-for='(craft_i, idx) in refuges[ 1 ][`craft_items`]' class='flex j-c ai-c fl-di-co' :class='[(idx !== 0) ? `ml5` : ``]'>
                            <div :class='rares[ items[ craft_i[`type`] ][ craft_i[`item`] ][`rare`] ][`border`]'>
                                <router-link :to='{path: `item`, query: {item: craft_i[`item`], type: craft_i[`type`]}}'>
                                    <img :src='items[ craft_i[`type`] ][ craft_i[`item`] ][`img`]' />
                                </router-link>
                            </div>
                            <div class='item-colvo backgr2 flex j-c ai-c'>
                            	{{ craft_i[`colvo`] }}
                            </div>
                        </div>
	                </div>
	            </div>
	            <up-refuge :move='2'></up-refuge>
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
		                    <span class='ml5'><img src='/assets/icons/info.png' />{{ refuges[ refuge[`lvl`] ][`nm`] }}</span>
		                </div>
		                <div class='iteminfo-div mt5'>
		                    <span class='ml5'><img src='/assets/icons/lvl.png' class='item14-1' /> Уровень: {{ refuge[`lvl`] }}</span>
		                </div>
		                <div class='iteminfo-div mt5'>
		                    <span class='ml5'><img src='/assets/icons/abs.png' class='item14-1' /> Броня: {{ refuges[ refuge[`lvl`] ][`dmgabs`] }}</span>
		                </div>
		            </div>
		            <div class='refuge-back relative flex j-c ai-e mt5' :style='{background: "url(" + locs[ 1 ][`img`] + ") no-repeat top/cover"}'>
		                <div class='refuge-hp-wrapper flex j-c'>
		                    <div class='refuge-hp-bar' :style='{width: hpPercent + "%"}'></div>
		                </div>
		                <div class='refuge-hp-colvo backgr1 flex j-c ai-c pl5 pr5 pb5 pt5'>
		                    <div class='flex j-c ai-c'>
		                        <img src='/assets/icons/hp.png' class='item14-1 mr5' /> <span>{{ refuge[`hp`] }} / {{ refuges[ refuge[`lvl`] ][`maxhp`] }}</span>
		                    </div>
		                </div>
		                <img :src='refuges[ refuge[`lvl`] ][`img`]' class='refuge-img block' :class='refuges[ refuge[`lvl`] ][`class`]' />
		            </div>

		            <div class='wdth96 flex j-c mt5'>
		                <hr class='hr-style mr5'> Компоненты <hr class='hr-style ml5'>
		            </div>
		            <div class='refuge-resource wdth86 backgr1 flex j-c ai-c mt5 mb5 pt5 pb5'>
	                    <div v-if='refuges[ Math.floor(refuge[`lvl`]) + 1 ]' class='wdth100 flex j-s ai-c ml5'>
	                        <div v-for='(craft_i, idx) in refuges[ Math.floor(refuge[`lvl`]) + 1 ][`craft_items`]' class='flex j-c ai-c fl-di-co' :class='[(idx !== 0) ? `ml5` : ``]'>
	                            <div :class='rares[ items[ craft_i[`type`] ][ craft_i[`item`] ][`rare`] ][`border`]'>
	                                <router-link :to='{path: `item`, query: {item: craft_i[`item`], type: craft_i[`type`]}}'>
	                                    <img :src='items[ craft_i[`type`] ][ craft_i[`item`] ][`img`]' />
	                                </router-link>
	                            </div>
	                            <div class='item-colvo backgr2 flex j-c ai-c'>
	                            	{{ craft_i[`colvo`] }}
	                            </div>
	                        </div>
	                    </div>
	                    <div v-else class='wdth100 flex j-c ai-c'>
	                        <span class='mess'>Максимальный уровень</span>
	                    </div>
		            </div>

		            <div class='refuge-moves flex j-c ai-c fl-di-co'>
		            	<up-refuge v-if='refuges[ Math.floor(refuge[`lvl`]) + 1 ]' :move='1'></up-refuge>
	                    <enter-refuge :user-enter='user[`in_refuge`]'></enter-refuge>
		            </div>
		        </div>
		    </div>

		    <div class='flex j-c ai-c fl-di-co mt10'>
		        <div class='refuge-protection-slots backgr2 flex j-c ai-c fl-di-co mt5 pt5 pb5'>
		            <div class='wdth96 flex j-c'>
		                <hr class='hr-style mr5'> Защита <hr class='hr-style ml5'>
		            </div>

		            <div v-if='refuges[ refuge[`lvl`] ][`prot`]' class='wdth96 flex j-c ai-c'>
		            	<div class='wdth96'>
		            		{{ prots.length }} / {{ refuges[ refuge[`lvl`] ][`prot`] }}
		            	</div>
		            </div>

		            <div class='wdth96 flex j-c ai-c fl-di-co'>
	                    <div v-if='refuges[ refuge[`lvl`] ][`prot`] == 0' class='protection-slot backgr1 flex j-c mt5'>
	                        Улучшите убежище
	                    </div>
                        <div v-else-if='prots.length > 0' v-for='prot in prots' class='protection-slot backgr1 flex j-c mt5'>
                            <div class='flex j-c ai-c'>
                                <div class='item32-1'>
                                    <div v-if='items[ 5 ][ prot[`item`] ]' class='flex j-c ai-c' :class='rares[ items[ 5 ][ prot[`item`] ][`rare`] ][`border`]'>
                                        <img :src='items[ 5 ][ prot[`item`] ][`img`]' />
                                    </div>
                                    <img v-else src='/assets/icons/slot.png' />
                                </div>
                                <div class='flex j-c fl-di-co'>
                                	<div v-if='items[ 5 ][ prot[`item`] ]'>
                                		<div class='item-name ml5 flex j-s'>
                                        	{{ items[ 5 ][ prot[`item`] ][`nm`] }}
                                        </div>
                                        <div class='item-rare ml5 flex j-s'>
                                            <span :class='rares[ items[ 5 ][ prot[`item`] ][`rare`] ][`class`]'>
                                                {{ rares[ items[ 5 ][ prot[`item`] ][`rare`] ][`word`] }}
                                            </span>
                                        </div>
                                	</div>
									<div v-else class='ml5'>Пусто</div>
                                </div>
                            </div>
                            <div class='flex j-c fl-di-co fl1'>
                                <div class='flex j-e ai-c'>
                                	<!-- plus -->
                                </div>
                            </div>
                        </div>
		                <div v-else class='wdth96 backgr1 pt5 pb5 flex j-s mt5'>
	                    	<span class='ml5'>Пусто</span>
	                    </div>
		            </div>

		            <div class='wdth96 flex j-c mt5'>
		                <hr class='hr-style mr5'> Инструменты <hr class='hr-style ml5'>
		            </div>

		            <div v-if='refuges[ refuge[`lvl`] ][`tools`]' class='wdth96 flex j-c ai-c'>
		            	<div class='wdth96'>
		            		{{ tools.length }} / {{ refuges[ refuge[`lvl`] ][`tools`] }}
		            	</div>
		            </div>

		            <div class='wdth96 flex j-c ai-c fl-di-co'>
	                    <div v-if='refuges[ refuge[`lvl`] ][`tools`] == 0' class='protection-slot backgr1 flex j-c mt5'>
	                        Улучшите убежище       
	                    </div>
                    	<div v-else-if='tools.length' v-for='tool in tools' class='protection-slot backgr1 flex j-c mt5'>
                            <div class='flex j-c ai-c'>
                                <div class='item32-1'>
                                    <div v-if='items[ 5 ][ tool[`item`] ]' class='flex j-c ai-c' :class='rares[ items[ 5 ][ tool[`item`] ][`rare`] ][`border`]'>
                                        <img :src='items[ 5 ][ tool[`item`] ][`img`]' />
                                    </div>
                                    <img v-else src='/assets/icons/slot.png' />
                                </div>
                                <div class='flex j-c fl-di-co'>
                                	<div v-if='items[ 5 ][ tool[`item`] ]'>
                                		<div class='item-name ml5 flex j-s'>
                                        	{{ items[ 5 ][ tool[`item`] ][`nm`] }}
                                        </div>
                                        <div class='item-rare ml5 flex j-s'>
                                            <span :class='rares[ items[ 5 ][ tool[`item`] ][`rare`] ][`class`]'>
                                                {{ rares[ items[ 5 ][ tool[`item`] ][`rare`] ][`word`] }}
                                            </span>
                                        </div>
                                	</div>
									<div v-else class='ml5'>Пусто</div>
                                </div>
                            </div>
                            <div class='flex j-c fl-di-co fl1'>
                                <div v-if='tool[`item`] == 1' class='flex j-e ai-c'>
                                	<div v-if='chest'>
                                		{{ chest }} / 50
                                	</div>
                                	<div v-else>
                                		0 / 50
                                	</div>
                                </div>
                            </div>
                        </div>
	                    <div v-else class='wdth96 backgr1 pt5 pb5 flex j-s mt5'>
	                    	<span class='ml5'>Пусто</span>
	                    </div>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
</template>

<script>
let date = Date.now();

let Tablo       = httpVueLoader('../components/Tablo.vue?_='+date)
let EnterRefuge = httpVueLoader('../components/EnterRefuge.vue?_='+date)
let UpRefuge    = httpVueLoader('../components/UpRefuge.vue?_='+date)

module.exports = {
	name: 'Refuge',
	data: () => ({
		api: false,

		items: undefined,
		rares: undefined,
		refuges: undefined,
		refuge: undefined,
		locs: undefined,

		user: undefined,
		game: undefined,
		tools: undefined,
		prots: undefined,
		chest: undefined
	}),
	components: {
		Tablo, EnterRefuge, UpRefuge
	},
	beforeMount() {
		let params = new FormData();
    	params.append('token', localStorage.getItem('token'));

		axios.post('/core/Api/?page=refuge', params)
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
				this.locs    = response.data.locs;
				
				this.user    = response.data.user;
				this.game    = response.data.game;
				this.tools   = response.data.tools;
				this.prots   = response.data.prots;
				this.chest   = response.data.chest;

				this.api = true;
			}
		})
		.catch((error) => {
			console.log(error)
		})
	},
	computed: {
		hpPercent() {
			return (this.refuge[`hp`] / this.refuges[ this.refuge[`lvl`] ][`maxhp`]) * 100;
		}
	}
}
</script>