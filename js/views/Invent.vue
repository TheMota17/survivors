<template>
	<div v-if='api'>
		<div class='flex j-c mt10'>
			<div class='ivent-pers backgr2 flex j-c ai-c pt5 pb5'>
				<div class='maneken relative flex j-c ai-c'>
		            <div :class='[nadeto[`helm`] == 0 ? `` : items[ 2 ][ nadeto[`helm`] ][`class`]]'></div>
		            <div :class='[nadeto[`arm`] == 0 ? `` : items[ 3 ][ nadeto[`arm`] ][`class`]]'></div>

					<div v-if='nadeto[`helm`] == 0' :class='`hair` + nadeto[`hair`]'></div>
					<div :class='`beard` + nadeto[`beard`]'></div>
					<div :class='`cloth` + nadeto[`cloth`]'></div>
					<div :class='`pants` + nadeto[`pants`]'></div>
					<div :class='`fwear` + nadeto[`fwear`]'></div>

					<img class='man' src='/assets/man/man.png' />
					<div class='maneken-shadow'></div>
				</div>

				<div class='user-info ml10'>
					<div class='user-name flex j-c ai-c'>
						<span class='user-name' id='user_name'>{{ user['login'] }}</span>
		                <img src='/assets/icons/hp.png' class='item14-1 ml5' /> {{ user['live'] }}
					</div>

					<div class='user-abs flex j-s ai-c mt5'>
						<img src='/assets/icons/abs.png' class='item14-1 ml5 mr5' />
						<span>
							{{ 
								((nadeto[`helm`] == 0) ? 0 : items[ 2 ][ nadeto[`helm`] ][`dmgabs`])
								+ 
								((nadeto[`arm`] == 0) ? 0 : items[ 3 ][ nadeto[`arm`] ][`dmgabs`])
							}}
						</span>
					</div>
					<div class='user-dmg flex j-s ai-c mt5'>
						<img src='/assets/icons/dmg.png' class='item14-1 ml5 mr5' />

		                <div v-if='nadeto[`weap`] > 0' class='flex j-s ai-c'>
		                	<span id='user_dmg_min'>
		                		{{ items[ 4 ][ nadeto[`weap`] ][`dmgmin`] }}
		    				</span> 
		    				- 
		    				<span id='user_dmg_max'>
		    					{{ items[ 4 ][ nadeto[`weap`] ][`dmgmax`] }}
		    				</span>
		                </div>
		                <div v-else class='flex j-s ai-c'>
		                	<span>0</span>
		                </div>
					</div>
					<div class='user-power flex j-s ai-c mt5'>
						<img src='/assets/icons/power.png' class='item14-1 ml5 mr5' />
						<span id='user_power'>
						{{
							((nadeto[`helm`] == 0) ? 0 : items[ 2 ][ nadeto[`helm`] ][`power`])
							+ 
							((nadeto[`arm`] == 0) ? 0 : items[ 3 ][ nadeto[`arm`] ][`power`])
							+ 
							((nadeto[`weap`] == 0) ? 0 : items[ 4 ][ nadeto[`weap`] ][`power`])
						}}
						</span>
					</div>

					<div class='user-hp-info flex j-c ai-c mt5'>
						<img src='/assets/icons/hung.png' class='item14-1 mr5' />
		                <div class='ivent-hung-bar'>
		                    <div class='hung-bar' :style='`width:` + game.hung + `%`'></div>
		                </div>
					</div>
					<div class='user-hung-info flex j-c ai-c mt5'>
						<img src='/assets/icons/thirst.png' class='item14-1 mr5' />
		                <div class='ivent-thirst-bar'>
		                    <div class='thirst-bar' :style='`width:` + game.thirst + `%`'></div>
		                </div>
					</div>
					<div class='user-fatigue-info flex j-c ai-c mt5'>
						<img src='/assets/icons/sleep.png' class='item14-1 mr5' />
						<div class='ivent-fatigue-bar'>
							<div class='fatigue-bar' :style='`width:` + game.fatigue + `%`'></div>
						</div>
					</div>
				</div>

			</div>
		</div>

		<div class='flex j-c ai-c fl-di-co mt5'>
			<div class='mb5'>
				Надето
			</div>
			<div class='pers-nadeto-items backgr2 flex j-c ai-c fl-di-co'>

				<div class='nadeto-helm backgr1 flex mt5'>
					<div class='flex j-c ai-c'>
						<div class='item32-1'>
							<div v-if='nadeto[`helm`] > 0' class='flex j-c ai-c' :class='rares[ items[2][ nadeto[`helm`] ][`rare`] ][`border`]'>
	                            <img :src='items[ 2 ][ nadeto[`helm`] ][`img`]' />
	                        </div>
	                        <img v-else src='/assets/icons/ivent-helm.png' />
				        </div>
				        <div class='flex j-c'>
				        	<div v-if='nadeto[`helm`] > 0' class='flex j-c fl-di-co'>
				        		<div class='item-name ml5 flex j-s'>
				        			{{ items[ 2 ][ nadeto[`helm`] ][`nm`] }}
		    		            </div>
		    		            <div class='item-rare ml5 flex j-s'>
		    		                <span :class='rares[ items[2][ nadeto[`helm`] ][`rare`] ][`class`]'>
		    		                	{{ rares[ items[2][ nadeto[`helm`] ][`rare`] ][`word`] }}
		    		                </span>
		    		            </div>
				        	</div>
				        	<div v-else>
				        		<div class='bolder ml5 flex j-s'>
		                            Голова
		                        </div>
		                        <div class='item-name ml5 flex j-s'>
		                            Ничего не надето
		                        </div>
				        	</div>
				        </div>
					</div>
		            <div v-if='nadeto[`helm`] > 0' class='flex j-c fl-di-co fl1'>
	                    <div class='item-dmg-abs flex j-e ai-c'>
	                        <img src='/assets/icons/abs.png' />
	                        <span>{{ items[ 2 ][ nadeto[`helm`] ][`dmgabs`] }}</span>
	                    </div>
	                    <div class='helm-power flex j-e ai-c'>
	                        <img src='/assets/icons/power.png' />
	                        <span>{{ items[ 2 ][ nadeto[`helm`] ][`power`] }}</span>
	                    </div>
		            </div>
				</div>

				<div class='nadeto-arm backgr1 flex mt5'>
					<div class='flex j-c ai-c'>
						<div class='item32-1'>
	                        <div v-if='nadeto[`arm`] > 0' class='flex j-c ai-c' :class='rares[ items[3][ nadeto[`arm`] ][`rare`] ][`border`]'>
	                        	<img :src='items[ 3 ][ nadeto[`arm`] ][`img`]' />
	                        </div>
	                        <img v-else src='/assets/icons/ivent-arm.png' />
				        </div>
				        <div class='flex j-c fl-di-co'>
				        	<div v-if='nadeto[`arm`] > 0' class='flex j-c fl-di-co'>
				        		<div class='item-name ml5 flex j-s'>
				        			{{ items[ 3 ][ nadeto[`arm`] ][`nm`] }}
		    		            </div>
		    		            <div class='item-rare ml5 flex j-s'>
		    		                <span :class='rares[ items[3][ nadeto[`arm`] ][`rare`] ][`class`]'>
		    		                	{{ rares[ items[3][ nadeto[`arm`] ][`rare`] ][`word`] }}
		    		                </span>
		    		            </div>
				        	</div>
	    		            <div v-else>
		                        <div class='bolder ml5 flex j-s'>
		                            Тело
		                        </div>
		                        <div class='item-name ml5 flex j-s'>
		                            Ничего не надето
		                        </div>
		                    </div>
				        </div>
					</div>
		            <div v-if='nadeto[`arm`] > 0' class='flex j-c fl-di-co fl1'>
	                    <div class='item-dmg-abs flex j-e ai-c'>
	                        <img src='/assets/icons/abs.png' />
	                        <span>{{ items[3][ nadeto[`arm`] ][`dmgabs`] }}</span>
	                    </div>
	                    <div class='helm-power flex j-e ai-c'>
	                        <img src='/assets/icons/power.png' />
	                        <span>{{ items[3][ nadeto[`arm`] ][`power`] }}</span>
	                    </div>
		            </div>
				</div>

				<div class='nadeto-weap backgr1 flex mt5 mb5'>
					<div class='flex j-c ai-c'>
						<div class='item32-1'>
							<div v-if='nadeto[`weap`] > 0' class='flex j-c ai-c' :class='rares[ items[4][ nadeto[`weap`] ][`rare`] ][`border`]'>
	                        	<img :src='items[ 4 ][ nadeto[`weap`] ][`img`]' />
	                        </div>
	                        <img v-else src='/assets/icons/ivent-weap.png' />
			            </div>
			            <div class='flex j-c fl-di-co'>
			            	<div v-if='nadeto[`weap`] > 0' class='flex j-c fl-di-co'>
				        		<div class='item-name ml5 flex j-s'>
				        			{{ items[ 4 ][ nadeto[`weap`] ][`nm`] }}
		    		            </div>
		    		            <div class='item-rare ml5 flex j-s'>
		    		                <span :class='rares[ items[4][ nadeto[`weap`] ][`rare`] ][`class`]'>
		    		                	{{ rares[ items[4][ nadeto[`weap`] ][`rare`] ][`word`] }}
		    		                </span>
		    		            </div>
				        	</div>
	    		            <div v-else>
		                        <div class='bolder ml5 flex j-s'>
		                            Оружие
		                        </div>
		                        <div class='item-name ml5 flex j-s'>
		                            Ничего не надето
		                        </div>
		                    </div>
			            </div>
					</div>
		            <div v-if='nadeto[`weap`] > 0' class='flex j-c fl-di-co fl1'>
		                <div class='item-dmg-abs flex j-e ai-c'>
	                        <img src='/assets/icons/dmg.png' />
	                        <span>{{ items[ 4 ][ nadeto[`weap`] ][`dmgmin`] }}</span> - <span>{{ items[ 4 ][ nadeto[`weap`] ][`dmgmax`] }}</span>
	                    </div>
	                    <div class='helm-power flex j-e ai-c'>
	                        <img src='/assets/icons/power.png' />
	                        <span>{{ items[ 4 ][ nadeto[`weap`] ][`power`] }}</span>
	                    </div>
		            </div>
				</div>

			</div>
		</div>

		<div class='flex j-c ai-c fl-di-co mt10'>
			<div class='flex j-c ai-c'>
				<span class='mr5'>Инвентарь</span>
		        <span>{{ invent.length }}</span> / 50
		        <button class='sort-btn flex j-c ai-c ml5' @click='sortMenu'>
		            <img src='/assets/icons/sort.png' class='mr5' /> {{ typeElems[ type ] }}
		        </button>
		    </div>
		    <div v-if='sort' class='sort-menu flex j-s ai-c mt5'>
		        <button @click='changeType(0)' class='flex j-c ai-c wdth100 mr5'>Все</button>
		        <button @click='changeType(2)' class='flex j-c ai-c wdth100 mr5'>Шлемы</button>
		        <button @click='changeType(3)' class='flex j-c ai-c wdth100 mr5'>Броня</button>
		        <button @click='changeType(4)' class='flex j-c ai-c wdth100 mr5'>Оружие</button>
		        <button @click='changeType(5)' class='flex j-c ai-c wdth100 mr5'>Убежище</button>
		        <button @click='changeType(1)' class='flex j-c ai-c wdth100'>Разное</button>
			</div>
			<div v-if='invent.length > 0' class='ivent-items backgr2 flex j-c ai-c fl-di-co mt5'>
				<router-link v-if='item.type == type || type == 0' v-for='item in paginatedData' :to='{ path: `item`, query: {id: item.id}}' class='item-div backgr1 flex j-sb mt5 mb5 pt5 pb5'>
	                <div class='fl1 flex j-s ai-c'>
	                    <div class='item32-2 flex j-c ai-c'>
	                        <div class='flex j-c ai-c' :class='rares[ items[ item[`type`] ][ item[`item`] ][`rare`] ][`border`]'>
	                            <img :src='items[ item[`type`] ][ item[`item`] ][`img`]' />
	                        </div>
	                    </div>
	                </div>

	                <div class='fl2 flex j-c fl-di-co color3 ml5'>
	                    <div class='ivent-item-name flex j-c'>
	                        {{ items[ item[`type`] ][ item[`item`] ][`nm`] }}
	                    </div>
	                    <div class='flex j-c'>
	                        Тип: {{ items[ item[`type`] ][ item[`item`] ][`type`] }}
	                    </div>
	                </div>

	                <div class='fl1 flex j-e'>
	                    <div class='item-colvo backgr2 flex j-c ai-c'>
	                    {{ item[`colvo`] }}
	                    </div>
	                </div>
	        	</router-link>
			</div>
			<div v-else class='ivent-items backgr2 flex j-c pt5 pb5 mt5'>
				Пусто
			</div>
			<div class='ivent-nav backgr2 flex j-c mt5 pt5 pb5'>
				<div class='wdth96 flex j-sb'>
					<button :disabled='page == 1' class='nav-btn ml5' @click='prevPage'>◄</button>
					<span class='nav-btn'>{{ page }}</span>
					<button :disabled='page >= pageCount' class='nav-btn mr5' @click='nextPage'>►</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
module.exports = {
    name: 'Invent',
    data: () => ({
    	api: false,

    	nadeto: undefined,
    	invent: undefined,
    	user: undefined,
    	game: undefined,
    	items: undefined,
    	rares: undefined,

    	max: 5,
    	page: 1,
    	type: 0,
    	sort: false,
    	typeElems: {0: 'Все', 1: 'Разное', 2: 'Шлемы', 3: 'Броня', 4: 'Оружие', 5: 'Убежище'}
    }),
	beforeMount() {
		let params = new FormData();
		params.append('token', localStorage.getItem('token'));

		axios.post('/core/ajax/api.php?page=invent', params)
		.then((response) => {
			if (response.data.popup) {
				this.$root.popup.active = true;
				this.$root.popup.text   = response.data.message;
			} else if (response.data.page) {
				this.$router.push(response.data.page)
			} else {
				this.nadeto = response.data.nadeto;
				this.invent = response.data.invent;
				this.user   = response.data.user;
				this.game   = response.data.game;
				this.items  = response.data.items;
				this.rares  = response.data.rares;

				this.api = true;
			}
		})
		.catch((error) => {
			console.log(error)
		})
	}, 
	methods: {
		nextPage() {
			this.page += 1;
		},
		prevPage() {
			this.page -= 1;
		},
		changeType(type) {
			this.type = type;
		},
		sortMenu() {
			this.sort = !this.sort;
		}
	},
	computed: {
		pageCount() {
	        let l = this.invent.length, s = this.max;
	        return Math.ceil(l/s);
    	},
    	paginatedData() {
    		const start = (this.page - 1) * this.max, end = start + this.max;
     		return this.invent.slice(start, end);
    	}
	}
}
</script>