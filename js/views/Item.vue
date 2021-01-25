<template>
	<div v-if='api'>
		<div class='flex j-c ai-c fl-di-co mt5'>
		    <div class='flex j-c'>
		        Предмет
		    </div>
		    <div class='item-iteminfo backgr2 pt5 pb5 mt5'>
		        <div class='flex ml5'>
		            <div class='iteminfo-img flex j-s'>
		                <div class='item32-1 flex j-c ai-c ml5'>
		                    <div class='flex j-c ai-c' :class='rares[ items[ item[`type`] ][ item[`item`] ][`rare`] ][`border`]'>
		                        <img :src='items[ item[`type`] ][ item[`item`] ][`img`]' />
		                    </div>
		                </div>
		            </div>
		            <div class='flex fl-di-co'>
		                <div class='iteminfo-name ml5'>
		                    {{ items[ item[`type`] ][ item[`item`] ][`nm`] }}
		                </div>
		                <div class='iteminfo-rare ml5'>
		                    <span :class='rares[ items[ item[`type`] ][ item[`item`] ][`rare`] ][`class`]'>
		                        {{ rares[ items[ item[`type`] ][ item[`item`] ][`rare`] ][`word`] }}
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
		                    <span class='ml5'>Тип: {{ items[ item[`type`] ][ item[`item`] ][`type`] }}</span>
		                </div>

		                <div v-if='items[ item[`type`] ][ item[`item`] ][`eff`]' v-for='(eff, key) in items[ item[`type`] ][ item[`item`] ][`eff`]' class='wdth100 flex j-c ai-c fl-di-co'>
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

		                <div v-if='items[ item[`type`] ][ item[`item`] ][`dmgabs`]' class='iteminfo-div flex j-sb mt5'>
	                        <div class='ml5'>
	                            <img src='/assets/icons/abs.png' class='item14-1' /> Подавление урона: {{ items[ item[`type`] ][ item[`item`] ][`dmgabs`] }}
	                        </div>
                            <div v-if='$route.query.id' class='ml5'>
                                <img v-if='items[ item[`type`] ][ item[`item`] ][`dmgabs`] > items[ item[`type`] ][ nadeto[ nElems[ item[`type`] ] ] ][`dmgabs`]' src='/assets/icons/better.png' class='item14-1 mr5' />
                              	<img v-else-if='items[ item[`type`] ][ nadeto[ nElems[item[`type`]] ] ][`dmgabs`] == items[ item[`type`] ][ item[`item`] ][`dmgabs`]' src='/assets/icons/equally.png' class='item14-1 mr5' />
                                <img v-else src='/assets/icons/worse.png' class='item14-1 mr5' />
                            </div>
	                    </div>
	                    <div v-else-if='items[ item[`type`] ][ item[`item`] ][`dmgmin`]' class='iteminfo-div flex j-sb mt5'>
	                        <div class='ml5'>
	                            <img src='/assets/icons/dmg.png' class='item14-1' /> Урон:
	                            {{ items[ item[`type`] ][ item[`item`] ][`dmgmin`] }}
	                            -
	                            {{ items[ item[`type`] ][ item[`item`] ][`dmgmax`] }}
	                        </div>
	                        <div v-if='$route.query.id' class='ml5'>
                                <img v-if='items[ item[`type`] ][ item[`item`] ][`dmgmin`] > items[ item[`type`] ][ nadeto[ nElems[ item[`type`] ] ] ][`dmgmin`]' src='/assets/icons/better.png' class='item14-1 mr5' />
                              	<img v-else-if='items[ item[`type`] ][ nadeto[ nElems[item[`type`]] ] ][`dmgmin`] == items[ item[`type`] ][ item[`item`] ][`dmgmin`]' src='/assets/icons/equally.png' class='item14-1 mr5' />
                                <img v-else src='/assets/icons/worse.png' class='item14-1 mr5' />
                            </div>
	                    </div>

	                    <div v-if='items[ item[`type`] ][ item[`item`] ][`power`]' class='iteminfo-div flex j-sb mt5'>
	                        <div class='ml5'>
	                            <img src='/assets/icons/power.png' class='item14-1' /> Бонус к мощи: {{ items[ item[`type`] ][ item[`item`] ][`power`] }}
	                        </div>
	                        <div v-if='$route.query.id' class='ml5'>
                                <img v-if='items[ item[`type`] ][ item[`item`] ][`power`] > items[ item[`type`] ][ nadeto[ nElems[ item[`type`] ] ] ][`power`]' src='/assets/icons/better.png' class='item14-1 mr5' />
                              	<img v-else-if='items[ item[`type`] ][ nadeto[ nElems[item[`type`]] ] ][`power`] == items[ item[`type`] ][ item[`item`] ][`power`]' src='/assets/icons/equally.png' class='item14-1 mr5' />
                                <img v-else src='/assets/icons/worse.png' class='item14-1 mr5' />
                            </div>
	                    </div>
		                    
	                    <div v-if='$route.query.id' class='iteminfo-div flex ai-c mt5'>
	                        <img src='/assets/icons/colvo.png' class='ml5 mr5 item14-1' /> Количество: {{ item[`colvo`] }}
	                    </div>
		            </div>
		        </div>
		        <div v-if='items[ item[`type`] ][ item[`item`] ][`ammu`]'>
		        	<div class='flex j-c mt10'>
		                <div class='wdth96 flex j-c'>
		                    <hr class='hr-style mr5'> Боеприпасы <hr class='hr-style ml5'>
		                </div>      
		            </div>
		            <div class='flex j-c ai-c pt5 pb5'>
		                <div class='wdth96 flex j-c'>
		                    <div class='wdth96 flex j-s'>
		                        <div v-for='ammu in items[ item[`type`] ][ item[`item`] ][`ammu`]' class='flex j-c ai-c fl-di-co mr5'>
	                                <div :class='rares[ items[ ammu[`t`] ][ ammu[`i`] ][`rare`] ][`border`]'>
	                                	<router-link :to='{path: `item`, query: {item: ammu[`i`], type: ammu[`t`], view: 1}}'>
	                                		<img :src='items[ ammu[`t`] ][ ammu[`i`] ][`img`]' />
	                                	</router-link>
	                                </div>
	                            </div>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>

		<div v-if='$route.query.id'>
			<nadet v-if='items[ item[`type`] ][ item[`item`] ][`move`] == `nadet`'></nadet>
			<eat v-else-if='items[ item[`type`] ][ item[`item`] ][`move`] == `eat`'></eat>
			<drink v-else-if='items[ item[`type`] ][ item[`item`] ][`move`] == `drink`'></drink>
			<read v-else-if='items[ item[`type`] ][ item[`item`] ][`move`] == `read`'></read>
			<place v-else-if='items[ item[`type`] ][ item[`item`] ][`move`] == `place`'></place>
		</div>

        <div v-if='item[`type`] !== 1 && item[`type`] !== 5 && $route.query.id && nadeto[ nElems[item[`type`]] ] > 0' class='flex j-c ai-c fl-di-co mt10'>
            <div class='flex j-c'>
                На вас надето
            </div>
            <div class='item-iteminfo backgr2 pt5 pb5 mt5'>
                <div class='flex ml5'>
                    <div class='iteminfo-img flex j-s'>
                        <div class='item32-1 flex j-c ai-c ml5'>
                        	<?=$game_rares[ $game_items[ $item['type'] ][ $nadeto[$nadeto_elems[$item['type']]] ][ 'rare' ] ]['border']?>
                            <div class='flex j-c ai-c' :class='rares[ items[ item[`type`] ][ nadeto[nElems[item[`type`]]] ][`rare`] ][`border`]'>
                                <img :src='items[ item[`type`] ][ nadeto[ nElems[item[`type`]] ] ][`img`]' />
                            </div>
                        </div>
                    </div>
                    <div class='flex fl-di-co'>
                        <div class='iteminfo-name ml5'>
                        	{{ items[ item[`type`] ][ nadeto[ nElems[item[`type`]] ] ][`nm`] }}
                        </div>
                        <div class='iteminfo-rare ml5'>
                            <span :class='rares[ items[ item[`type`] ][ nadeto[nElems[item[`type`]]] ][`rare`] ][`class`]'>
                                {{ rares[ items[ item[`type`] ][ nadeto[nElems[item[`type`]]] ][`rare`] ][`word`] }}
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
                            {{ items[ item[`type`] ][ nadeto[ nElems[item[`type`]] ] ][`type`] }}
                        </span>
                    </div>
                    <div v-if='items[ item[`type`] ][ item[`item`] ][`dmgabs`]' class='iteminfo-div mt5'>
                        <span class='ml5'>
                            <img src='/assets/icons/abs.png' class='item14-1' />
                            Подавление урона: {{ items[ item[`type`] ][ nadeto[ nElems[item[`type`]] ] ][`dmgabs`] }}
                        </span>
                    </div>
                    <div v-else class='iteminfo-div mt5'>
                        <span class='ml5'>
                            <img src='/assets/icons/dmg.png' class='item14-1' /> Урон:
                            {{ items[ item[`type`] ][ nadeto[ nElems[item[`type`]] ] ][`dmgmin`] }}
                            -
                            {{ items[ item[`type`] ][ nadeto[ nElems[item[`type`]] ] ][`dmgmax`] }}
                        </span>
                    </div>
                    <div class='iteminfo-div mt5'>
                        <span class='ml5'>
                            <img src='/assets/icons/power.png' class='item14-1' /> Бонус к мощи: {{ items[ item[`type`] ][ nadeto[ nElems[item[`type`]] ] ][`power`] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
	</div>
</template>

<script>
let Nadet = httpVueLoader('../components/Nadet.vue')
let Eat   = httpVueLoader('../components/Eat.vue')
let Drink = httpVueLoader('../components/Drink.vue')
let Read  = httpVueLoader('../components/Read.vue')
let Place = httpVueLoader('../components/Place.vue')

module.exports = {
	name: 'Item',
	data: () => ({
		api: false,

		item: undefined,
		items: undefined,
		pred: undefined,
		rares: undefined,
		nadeto: undefined,
		nElems: undefined
	}),
	components: {
		Nadet, Eat, Drink, Read, Place
	},
	beforeMount() {
		let params = new FormData();
		params.append('id', this.$route.query.id);
    	params.append('token', localStorage.getItem('token'));

		axios.post('/core/ajax/api.php?page=item', params)
		.then((response) => {
			if (response.data.popup) {
				this.$root.popup.active = true;
				this.$root.popup.text   = response.data.message;
			} else if (response.data.page) {
				this.$router.push(response.data.page)
			} else {
				this.items  = response.data.items;
				this.pred   = response.data.pred;
				this.rares  = response.data.rares;
				this.nadeto = response.data.nadeto;
				this.nElems = response.data.nElems;
			
				if (!response.data.item) {
					if (this.$route.query.type && this.$route.query.item) {
						this.item = {type: Math.floor(this.$route.query.type), item: Math.floor(this.$route.query.item)};

						this.api = true;
					} else {
						this.$router.push('/')
					}
				} else {
					this.item = response.data.item;
					this.api  = true;
				}
			}
		})
		.catch((error) => {
			console.log(error)
		})
	}
}
</script>