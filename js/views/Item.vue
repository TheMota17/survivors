<template>
	<div v-if='api'>
		<div class='flex j-c ai-c fl-di-co mt5'>
			<div class='zag-style flex j-c ai-c'>
		        Предмет
		    </div>
		    <div class='item-iteminfo backgr2 pt5 pb5 mt5'>
		        <div class='flex ml5 mt5'>
		            <div class='iteminfo-img flex j-s ml5'>
		                <item-output :items='[{item: item[`item`], type: item[`type`]}]' :sys-items='items' :sys-rares='rares'></item-output>
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
		                <div class='iteminfo-div flex'>
		                    <div class='ml5'>
		                    	Тип:
		                    </div>
		                    <div class='fl1 flex j-e'>
		                    	<span class='mr5'>{{ items[ item[`type`] ][ item[`item`] ][`type`] }}</span>
		                    </div>
		                </div>

		                <div v-if='items[ item[`type`] ][ item[`item`] ][`eff`]' v-for='(eff, key) in items[ item[`type`] ][ item[`item`] ][`eff`]' class='wdth100 flex j-c ai-c fl-di-co'>
	                		<div v-if='key == `hung`' class='iteminfo-div flex mt5'>
                                <div class='ml5'>
                                    <img src='/assets/icons/hung.png' class='item14-1' />
                                    Голод:
                                </div>
                                <div class='fl1 flex j-e'>
                                	<span class='mr5'>-{{ eff }}</span>
                                </div>
                            </div>
                            <div v-else-if='key == `thirst`' class='iteminfo-div flex mt5'>
                                <div class='ml5'>
                                    <img src='/assets/icons/thirst.png' class='item14-1' />
                                    Жажда:
                                </div>
                                <div class='fl1 flex j-e'>
                                	<span class='mr5'>-{{ eff }}</span>
                                </div>
                            </div>
                            <div v-else-if='key == `hp`' class='iteminfo-div flex mt5'>
                                <div class='ml5'>
                                    <img src='/assets/icons/hp.png' class='item14-1' />
                                    Здоровье:
                                </div>
                                <div class='fl1 flex j-e'>
                                	<span class='mr5'>+{{ eff }}</span>
                                </div>
                            </div>
	                	</div>

		                <div v-if='items[ item[`type`] ][ item[`item`] ][`dmgabs`]' class='iteminfo-div flex j-sb mt5'>
	                        <div class='ml5'>
	                            <img src='/assets/icons/abs.png' class='item14-1' /> Подавление урона:
	                        </div>
	                        <div class='fl1 flex j-e'>
	                        	<span class='mr5'>
	                        		{{ items[ item[`type`] ][ item[`item`] ][`dmgabs`] }}
	                        	</span>
	                        </div>
                            <div v-if='$route.query.id && nadeto[ nElems[ item[`type`] ] ]'>
                                <img v-if='items[ item[`type`] ][ item[`item`] ][`dmgabs`] > items[ item[`type`] ][ nadeto[ nElems[ item[`type`] ] ] ][`dmgabs`]' src='/assets/icons/better.png' class='item14-1 mr5' />
                              	<img v-else-if='items[ item[`type`] ][ nadeto[ nElems[item[`type`]] ] ][`dmgabs`] == items[ item[`type`] ][ item[`item`] ][`dmgabs`]' src='/assets/icons/equally.png' class='item14-1 mr5' />
                                <img v-else src='/assets/icons/worse.png' class='item14-1 mr5' />
                            </div>
	                    </div>

	                    <div v-else-if='items[ item[`type`] ][ item[`item`] ][`dmgmin`]' class='iteminfo-div flex j-sb mt5'>
	                        <div class='ml5'>
	                            <img src='/assets/icons/dmg.png' class='item14-1' /> Урон:
	                        </div>
	                        <div class='fl1 flex j-e'>
	                        	<span class='mr5'>
	                        		{{ items[ item[`type`] ][ item[`item`] ][`dmgmin`] }}
		                            -
		                            {{ items[ item[`type`] ][ item[`item`] ][`dmgmax`] }}
	                        	</span>
	                        </div>
	                        <div v-if='$route.query.id && nadeto[ nElems[ item[`type`] ] ]' class='flex ai-c'>
                                <img v-if='items[ item[`type`] ][ item[`item`] ][`dmgmin`] > items[ item[`type`] ][ nadeto[ nElems[ item[`type`] ] ] ][`dmgmin`]' src='/assets/icons/better.png' class='item14-1 mr5' />
                              	<img v-else-if='items[ item[`type`] ][ nadeto[ nElems[item[`type`]] ] ][`dmgmin`] == items[ item[`type`] ][ item[`item`] ][`dmgmin`]' src='/assets/icons/equally.png' class='item14-1 mr5' />
                                <img v-else src='/assets/icons/worse.png' class='item14-1 mr5' />
                            </div>
	                    </div>

	                    <div v-if='items[ item[`type`] ][ item[`item`] ][`power`]' class='iteminfo-div flex j-sb mt5'>
	                        <div class='ml5'>
	                            <img src='/assets/icons/power.png' class='item14-1' /> Бонус к мощи:
	                        </div>
	                        <div class='fl1 flex j-e'>
	                        	<span class='mr5'>{{ items[ item[`type`] ][ item[`item`] ][`power`] }}</span>
	                        </div>
	                        <div v-if='$route.query.id && nadeto[ nElems[ item[`type`] ] ]' class='flex ai-c'>
                                <img v-if='items[ item[`type`] ][ item[`item`] ][`power`] > items[ item[`type`] ][ nadeto[ nElems[ item[`type`] ] ] ][`power`]' src='/assets/icons/better.png' class='item14-1 mr5' />
                              	<img v-else-if='items[ item[`type`] ][ nadeto[ nElems[item[`type`]] ] ][`power`] == items[ item[`type`] ][ item[`item`] ][`power`]' src='/assets/icons/equally.png' class='item14-1 mr5' />
                                <img v-else src='/assets/icons/worse.png' class='item14-1 mr5' />
                            </div>
	                    </div>

	                    <div v-if='$route.query.id' class='iteminfo-div flex ai-c mt5'>
	                    	<div class='flex ai-c'>
	                    		<img src='/assets/icons/colvo.png' class='item14-1 ml5 mr5' /> Количество:
	                    	</div>
	                    	<div class='fl1 flex j-e'>
		                    	<span class='mr5'>{{ item[`colvo`] }}</span>
		                    </div>
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
		                    	<item-output :items='items[ item[`type`] ][ item[`item`] ][`ammu`]' :sys-items='items' :sys-rares='rares'></item-output>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>

		<div v-if='$route.query.id' class='flex j-c'>
			<div class='item-moves backgr2 flex j-c ai-c fl-di-co pb5'>
				<nadet v-if='items[ item[`type`] ][ item[`item`] ][`move`] == `nadet`'></nadet>
				<eat v-else-if='items[ item[`type`] ][ item[`item`] ][`move`] == `eat`'></eat>
				<drink v-else-if='items[ item[`type`] ][ item[`item`] ][`move`] == `drink`'></drink>
				<read v-else-if='items[ item[`type`] ][ item[`item`] ][`move`] == `read`'></read>
				<place v-else-if='items[ item[`type`] ][ item[`item`] ][`move`] == `place`'></place>
			</div>
		</div>

        <div v-if='item[`type`] !== 1 && item[`type`] !== 5 && $route.query.id && nadeto[ nElems[item[`type`]] ] > 0' class='flex j-c ai-c fl-di-co mt10'>
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
                        	{{ items[ item[`type`] ][ nadeto[ nElems[item[`type`]] ] ][`nm`] }} <span class='bolder'>(На вас надето)</span>
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
                		<div class='iteminfo-div flex'>
	                        <div class='ml5'>
	                            Тип:
	                        </div>
	                        <div class='fl1 flex j-e'>
	                        	<span class='mr5'>{{ items[ item[`type`] ][ nadeto[ nElems[item[`type`]] ] ][`type`] }}</span>
	                        </div>
	                    </div>
	                    <div v-if='items[ item[`type`] ][ item[`item`] ][`dmgabs`]' class='iteminfo-div flex mt5'>
	                        <div class='ml5'>
	                            <img src='/assets/icons/abs.png' class='item14-1' />
	                            Подавление урона:
	                        </div>
	                        <div class='fl1 flex j-e'>
	                        	<span class='mr5'>
	                        		{{ items[ item[`type`] ][ nadeto[ nElems[item[`type`]] ] ][`dmgabs`] }}
	                        	</span>
	                        </div>
	                    </div>
	                    <div v-else class='iteminfo-div flex mt5'>
	                        <div class='ml5'>
	                            <img src='/assets/icons/dmg.png' class='item14-1' /> Урон:
	                        </div>
	                        <div class='fl1 flex j-e'>
	                        	<span class='mr5'>
		                            {{ items[ item[`type`] ][ nadeto[ nElems[item[`type`]] ] ][`dmgmin`] }}
		                            -
		                            {{ items[ item[`type`] ][ nadeto[ nElems[item[`type`]] ] ][`dmgmax`] }}
	                        	</span>
	                        </div>
	                    </div>
	                    <div class='iteminfo-div flex mt5'>
	                        <div class='ml5'>
	                            <img src='/assets/icons/power.png' class='item14-1' /> Бонус к мощи:
	                        </div>
	                        <div class='fl1 flex j-e'>
	                        	<span class='mr5'>{{ items[ item[`type`] ][ nadeto[ nElems[item[`type`]] ] ][`power`] }}</span>
	                        </div>
	                    </div>
                	</div>
                </div>
            </div>
        </div>
	</div>
</template>

<script>
let date = Date.now();
let ItemOutput = httpVueLoader('../components/ItemOutput.vue?_='+date)
let Nadet      = httpVueLoader('../components/Nadet.vue?_='+date)
let Eat        = httpVueLoader('../components/Eat.vue?_='+date)
let Drink      = httpVueLoader('../components/Drink.vue?_='+date)
let Read       = httpVueLoader('../components/Read.vue?_='+date)
let Place      = httpVueLoader('../components/Place.vue?_='+date)

module.exports = {
	name: 'Item',
	data: () => ({
		api: false,

		item: undefined,
		items: undefined,
		rares: undefined,
		nadeto: undefined,
		nElems: undefined,

		game: undefined
	}),
	components:
	{
		ItemOutput, Nadet, Eat, Drink, Read, Place
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