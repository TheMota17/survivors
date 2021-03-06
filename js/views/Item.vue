<template>
	<div v-if='api'>
		<div class='flex j-c ai-c fl-di-co'>
		    <div class='item-iteminfo backgr2 pt5 pb5 mt5'>
		    	<div class='zag-style flex j-c ai-c'>
			        Предмет
			    </div>
		        <div class='flex ml5 mt5'>
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
                            <div v-if='$route.query.id && nadeto[ nElems[ item[`type`] ] ]' class='ml5'>
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
	                        <div v-if='$route.query.id && nadeto[ nElems[ item[`type`] ] ]' class='ml5'>
                                <img v-if='items[ item[`type`] ][ item[`item`] ][`dmgmin`] > items[ item[`type`] ][ nadeto[ nElems[ item[`type`] ] ] ][`dmgmin`]' src='/assets/icons/better.png' class='item14-1 mr5' />
                              	<img v-else-if='items[ item[`type`] ][ nadeto[ nElems[item[`type`]] ] ][`dmgmin`] == items[ item[`type`] ][ item[`item`] ][`dmgmin`]' src='/assets/icons/equally.png' class='item14-1 mr5' />
                                <img v-else src='/assets/icons/worse.png' class='item14-1 mr5' />
                            </div>
	                    </div>

	                    <div v-if='items[ item[`type`] ][ item[`item`] ][`power`]' class='iteminfo-div flex j-sb mt5'>
	                        <div class='ml5'>
	                            <img src='/assets/icons/power.png' class='item14-1' /> Бонус к мощи: {{ items[ item[`type`] ][ item[`item`] ][`power`] }}
	                        </div>
	                        <div v-if='$route.query.id && nadeto[ nElems[ item[`type`] ] ]' class='ml5'>
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

		<div v-if='$route.query.id' class='flex j-c'>
			<div class='item-moves backgr2 flex j-c ai-c fl-di-co pb5'>
				<nadet v-if='items[ item[`type`] ][ item[`item`] ][`move`] == `nadet` && item[`in_chest`] == 0'></nadet>
				<eat v-else-if='items[ item[`type`] ][ item[`item`] ][`move`] == `eat` && item[`in_chest`] == 0' class='mt5'></eat>
				<drink v-else-if='items[ item[`type`] ][ item[`item`] ][`move`] == `drink` && item[`in_chest`] == 0' class='mt5'></drink>
				<read v-else-if='items[ item[`type`] ][ item[`item`] ][`move`] == `read` && item[`in_chest`] == 0' class='mt5'></read>
				<place v-else-if='items[ item[`type`] ][ item[`item`] ][`move`] == `place` && item[`in_chest`] == 0' class='mt5'></place>
				<get-from-chest v-if='item[`in_chest`] == 1' class='mt5'></get-from-chest>
				<place-to-chest v-else-if='chest' class='mt5'></place-to-chest>
			</div>
		</div>

        <div v-if='item[`type`] !== 1 && item[`type`] !== 5 && $route.query.id && nadeto[ nElems[item[`type`]] ] > 0' class='flex j-c ai-c fl-di-co mt10'>
            <div class='flex j-c'>
                На вас надето
            </div>
            <div class='item-iteminfo backgr2 pt5 pb5 mt5'>
                <div class='flex ml5'>
                    <div class='iteminfo-img flex j-s'>
                        <div class='item32-1 flex j-c ai-c ml5'>
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
                <div class='wdth100 flex j-c mt5'>
                	<div class='wdth96 flex j-c ai-c fl-di-co'>
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
	</div>
</template>

<script>
let date = Date.now();
let Nadet = httpVueLoader('../components/Nadet.vue?_='+date)
let Eat   = httpVueLoader('../components/Eat.vue?_='+date)
let Drink = httpVueLoader('../components/Drink.vue?_='+date)
let Read  = httpVueLoader('../components/Read.vue?_='+date)
let Place = httpVueLoader('../components/Place.vue?_='+date)
let PlaceToChest = httpVueLoader('../components/PlaceToChest.vue?_='+date)
let GetFromChest = httpVueLoader('../components/GetFromChest.vue?_='+date)

module.exports = {
	name: 'Item',
	data: () => ({
		api: false,

		item: undefined,
		items: undefined,
		rares: undefined,
		nadeto: undefined,
		nElems: undefined,

		chest: undefined,
		game: undefined
	}),
	components:
	{
		Nadet, Eat, Drink, Read, Place, PlaceToChest, GetFromChest
	},
	beforeMount()
	{
		let params = new FormData();
		params.append('id', this.$route.query.id);
    	params.append('token', localStorage.getItem('token'));

		axios.post('/core/Api/?page=item', params)
		.then((response) => {
			if (response.data.popup)
			{
				this.$root.popup.active = true;
				this.$root.popup.text   = response.data.message;
			} else if (response.data.page)
			{
				this.$router.push(response.data.page)
			} else {
				this.items  = response.data.items;
				this.rares  = response.data.rares;
				this.nadeto = response.data.nadeto;
				this.nElems = response.data.nElems;
				this.game   = response.data.game;
				this.chest  = response.data.chest;

				this.$root.tablo.hp = this.game.hp;
                this.$root.tablo.hung = this.game.hung;
                this.$root.tablo.thirst = this.game.thirst;
                this.$root.tablo.fatigue = this.game.fatigue;

				if (!response.data.item)
				{
					if (this.$route.query.type && this.$route.query.item)
					{
						this.item = {type: Math.floor(this.$route.query.type), item: Math.floor(this.$route.query.item)};

						this.api = true;
					} else
					{
						this.$router.push('/')
					}
				} else
				{
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