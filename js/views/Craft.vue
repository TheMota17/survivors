<template>
	<div v-if='api'>
		<div class='flex j-c ai-c fl-di-co mt5'>
		    <div class='craft-items backgr2 flex j-c ai-c fl-di-co'>
		    	<div class='zag-style flex j-c ai-c mt5 mb5'>
			        Крафт
			    </div>
	            <div class='sort-menu flex j-s ai-c'>
			        <button @click='changeType(2)' class='flex j-c ai-c mr5'>
			        	<img class='item14-1' src='/assets/items/helms/wood-helm.png' />
			    	</button>
			        <button @click='changeType(3)' class='flex j-c ai-c mr5'>
			        	<img class='item14-1' src='/assets/icons/abs.png' />
			    	</button>
			        <button @click='changeType(4)' class='flex j-c ai-c mr5'>
			        	<img class='item14-1' src='/assets/icons/dmg.png' />
			    	</button>
			        <button @click='changeType(5)' class='flex j-c ai-c mr5'>
			        	<img class='item14-1' src='/assets/icons/menu/refuge.png' />
			        </button>
			        <button @click='changeType(1)' class='flex j-c ai-c'>
			        	<img class='item14-1' src='/assets/icons/diff.png' />
			    	</button>
				</div>
	            <div v-if='$root.craft.selected' class='craft-item backgr1 flex j-c fl-di-co mb5 pt5 pb5'>
	                <div class='craft-first-info flex'>
	                    <div class='flex j-c ai-c ml5' :class='rares[ items[ $root.craft.type ][ $root.craft.item ][`rare`] ][`border`]'>
	                        <img :src='items[ $root.craft.type ][ $root.craft.item ][`img`]'>
	                    </div>
	                    <div class='flex j-s fl-di-co'>
	                        <div class='item-name ml5 flex j-s'>
	                            {{ items[ $root.craft.type ][ $root.craft.item ][`nm`] }}
	                        </div>
	                        <div class='item-rare ml5 flex j-s'>
	                            <span :class='rares[ items[ $root.craft.type ][ $root.craft.item ][`rare`] ][`class`]'>
	                                {{ rares[ items[ $root.craft.type ][ $root.craft.item ][`rare`] ][`word`] }}
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
	                    	<div class='wdth96 flex j-c ai-c fl-di-co'>
	                    		<div class='iteminfo-div'>
		                            <span class='ml5'>Тип: {{ items[ $root.craft.type ][ $root.craft.item ][`type`] }}</span>
		                        </div>
		                        <div v-if='items[ $root.craft.type ][ $root.craft.item ][`eff`]' v-for='(eff, key) in items[ $root.craft.type ][ $root.craft.item ][`eff`]' class='wdth100 flex j-c ai-c fl-di-co'>
			                		<div v-if='key == `hung`' class='iteminfo-div mt5'>
		                                <span class='ml5'>
		                                    <img src='/assets/icons/hung.png' class='item14-1' />
		                                    Голод: -{{ eff }}
		                                </span>
		                            </div>
		                            <div v-else-if='key == `thirst`' class='iteminfo-div mt5'>
		                                <span class='ml5'>
		                                    <img src='/assets/icons/thirst.png' class='item14-1' />
		                                    Жажда: -{{ eff }}
		                                </span>
		                            </div>
		                            <div v-else-if='key == `hp`' class='iteminfo-div mt5'>
		                                <span class='ml5'>
		                                    <img src='/assets/icons/hp.png' class='item14-1' />
		                                    Здоровье: +{{ eff }}
		                                </span>
		                            </div>
			                	</div>

		                        <div v-if='items[ $root.craft.type ][ $root.craft.item ][`dmgabs`]' class='iteminfo-div flex j-sb mt5'>
			                        <div class='ml5'>
			                            <img src='/assets/icons/abs.png' class='item14-1' /> Подавление урона: {{ items[ $root.craft.type ][ $root.craft.item ][`dmgabs`] }}
			                        </div>
			                    </div>
			                    <div v-else-if='items[ $root.craft.type ][ $root.craft.item ][`dmgmin`]' class='iteminfo-div flex j-sb mt5'>
			                        <div class='ml5'>
			                            <img src='/assets/icons/dmg.png' class='item14-1' /> Урон:
			                            {{ items[ $root.craft.type ][ $root.craft.item ][`dmgmin`] }}
			                            -
			                            {{ items[ $root.craft.type ][ $root.craft.item ][`dmgmax`] }}
			                        </div>
			                    </div>

		                        <div v-if='items[ $root.craft.type ][ $root.craft.item ][`power`]' class='iteminfo-div flex j-sb mt5'>
			                        <div class='ml5'>
			                            <img src='/assets/icons/power.png' class='item14-1' /> Бонус к мощи: {{ items[ $root.craft.type ][ $root.craft.item ][`power`] }}
			                        </div>
			                    </div>
	                    	</div>
	                    </div>
	                    <div class='flex j-c mt10'>
	                        <div class='wdth96 flex j-с'>
	                            <hr class='hr-style mr5'> Компоненты <hr class='hr-style ml5'>
	                        </div>
	                    </div>
	                    <div class='flex j-c mt5'>
	                        <div class='wdth96 flex j-s'>
	                            <div v-for='(craft_i, idx) in crafts[ $root.craft.id ][`craft_items`]' class='flex j-c ai-c fl-di-co' :class='[(idx !== 0) ? `ml5` : ``]'>
                                    <div :class='rares[ items[ craft_i[`type`] ][ craft_i[`item`] ][`rare`] ][`border`]'>
                                        <router-link :to='{path: `item`, query: {item: craft_i[`item`], type: craft_i[`type`]}}'>
                                            <img :src='items[ craft_i[`type`] ][ craft_i[`item`] ][`img`]' />
                                        </router-link>
                                    </div>
                                    <div class='item-colvo backgr1 flex j-c ai-c'>
                                    	{{ craft_i[`colvo`] }}
                                    </div>
                                </div>
	                        </div>
	                    </div>
	                   	<div v-if='crafts[ $root.craft.id ][`tools`]'>
	                   		<div class='flex j-c mt10'>
	                            <div class='wdth96 flex j-с'>
	                                <hr class='hr-style mr5'> Инструменты <hr class='hr-style ml5'>
	                            </div>
	                        </div>
	                        <div class='flex j-c mt5'>
	                            <div class='wdth96 flex j-s'>
	                                <div v-for='(tool, idx) in crafts[ $root.craft.id ][`tools`]' class='flex j-c ai-c fl-di-co' :class='[(idx !== 0) ? `ml5` : ``]'>
	                                    <div :class='rares[ items[ tool[`type`] ][ tool[`item`] ][`rare`] ][`border`]'>
	                                        <router-link :to='{path: `item`, query: {item: tool[`item`], type: tool[`type`]}}'>
	                                            <img :src='items[ tool[`type`] ][ tool[`item`] ][`img`]' />
	                                        </router-link>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                   	</div>
	                    <div v-if='items[ $root.craft.type ][ $root.craft.item ][`ammu`]'>
				        	<div class='flex j-c mt10'>
				                <div class='wdth96 flex j-c'>
				                    <hr class='hr-style mr5'> Боеприпасы <hr class='hr-style ml5'>
				                </div>
				            </div>
				            <div class='flex j-c ai-c pt5 pb5'>
				                <div class='wdth96 flex j-c'>
				                    <div class='wdth100 flex j-s'>
				                        <div v-for='(ammu, idx) in items[ $root.craft.type ][ $root.craft.item ][`ammu`]' class='flex j-c ai-c fl-di-co' :class='[(idx !== 0) ? `ml5` : ``]'>
			                                <div :class='rares[ items[ ammu[`t`] ][ ammu[`i`] ][`rare`] ][`border`]'>
			                                	<router-link :to='{path: `item`, query: {item: ammu[`i`], type: ammu[`t`]}}'>
			                                		<img :src='items[ ammu[`t`] ][ ammu[`i`] ][`img`]' />
			                                	</router-link>
			                                </div>
			                            </div>
				                    </div>
				                </div>
				            </div>
				        </div>
	                    <div class='flex j-c mt10'>
	                        <div class='wdth96 flex j-s'>
	                            <hr class='hr-style mr5'> Количество <hr class='hr-style ml5'>
	                        </div>
	                    </div>
	                    <div class='flex j-c mt5 mb5'>
	                        <div class='item-colvo backgr1 color3 flex j-c ai-c'>{{ colvo }}</div>
	                    </div>
	                    <div class='flex j-c mb5'>
	                        <input class='colvo-range-input mt5' type='range' min='1' max='50' step='1' value='1' v-model='colvo'>
	                    </div>
	                    <div class='flex j-c mt5 mb5'>
	                        <button class='moves-btn relative mt5 flex j-s ai-c' @click='craft'>
	                            <div class='game-btn-icon ml5 mr5 flex j-c ai-c'>
	                                <img src='/assets/icons/menu/craft.png' />
	                            </div>
	                            <div class='flex j-c ai-c'>Создать</div>
	                            <div class='game-btn-bar'></div>
	                        </button>
	                    </div>
	                </div>
	            </div>
		        <div v-else class='wdth100 flex j-c ai-c fl-di-co'>
		            <div v-for='(craft, idx) in crafts' v-if='craft[`craft_lvl`] <= user[`craft_lvl`] && $root.craft.sortType == craft[`type`]' class='craft-item backgr1 flex j-c fl-di-co mt5 mb5 pt5 pb5'>
	            		<div class='craft-first-info flex'>
	                        <div class='flex j-c ai-c ml5' :class='rares[ items[ craft[`type`] ][ craft[`item`] ][`rare`] ][`border`]'>
	                            <img :src='items[ craft[`type`] ][ craft[`item`] ][`img`]'>
	                        </div>
	                        <div class='flex j-s fl-di-co'>
	                            <div class='item-name ml5 flex j-s'>
	                                {{ items[ craft[`type`] ][ craft[`item`] ][`nm`] }}
	                            </div>
	                            <div class='item-rare ml5 flex j-s'>
	                                <span :class='rares[ items[ craft[`type`] ][ craft[`item`] ][`rare`] ][`class`]'>
	                                    {{ rares[ items[ craft[`type`] ][ craft[`item`] ][`rare`] ][`word`] }}
	                                </span>
	                            </div>
	                        </div>
	                        <div class='ml5 mr5 fl1 flex j-e'>
	                            <button class='craft-item-info flex j-c ai-c' @click='selectItem(idx, craft[`item`], craft[`type`])'>
	                                <img src='/assets/icons/menu/craft.png'/>
	                            </button>
	                        </div>
                    	</div>
	                </div>
		        </div>
		        <div v-if='$root.craft.selected' class='wdth100 flex j-c mt5 mb5'>
		        	<div class='wdth96 flex j-e'>
		        		<button class='craft-back-btn' @click='awayItem'>Назад</button>
		        	</div>
		    	</div>
		    </div>
		</div>
	</div>
</template>

<script>
module.exports = {
	name: 'Craft',
	data: () => ({
		api: false,

		items: undefined,
		crafts: undefined,
		rares: undefined,
		user: undefined,
		game: undefined,

		colvo: 1
	}),
	beforeMount()
	{
		let params = new FormData();
    	params.append('token', localStorage.getItem('token'));

		axios.post('/core/Api/?page=craft', params)
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
				this.items  = response.data.items;
				this.crafts = response.data.crafts;
				this.rares  = response.data.rares;
				this.user   = response.data.user;
				this.game   = response.data.game;

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
	},
	methods: {
		changeType(type)
		{
			this.$root.craft.sortType = type;
		},
		selectItem(idx, item, type)
		{
			this.$root.craft.id       = idx;
			this.$root.craft.item     = item;
			this.$root.craft.type     = type;
			this.$root.craft.selected = true;
		},
		awayItem()
		{
			this.$root.craft.id       = 0;
			this.$root.craft.item     = 0;
			this.$root.craft.type     = 0;
			this.$root.craft.selected = false;
		},
		craft()
		{
			let params = new FormData();
			params.append('id', this.$root.craft.id);
			params.append('colvo', this.colvo);
	    	params.append('token', localStorage.getItem('token'));

			axios.post('/core/GameActions/?action=craft', params)
			.then((response) => {
				if (response.data.popup)
				{
					this.$root.popup.active = true;
					this.$root.popup.text   = response.data.message;
				} else if (response.data.page)
				{
					this.$router.push(response.data.page)
				}
			})
			.catch((error) => {
				console.log(error)
			})
		}
	}
}
</script>