<template>
	<div>
        <div class='zag-style flex j-c mt5'>
            <div class='fnt14 mt5'>Настройте персонажа</div>
        </div>

        <div class='flex j-c mt5'>
            <div class='cost-message backgr2 flex j-c ai-c pt5 pb5'>
                <div class='wdth90 flex j-c ai-c'>
                    <img src='/assets/icons/mess.png' class='mr5'>
                    <span class='mess'>След. настройка будет доступна за игровую валюту</span>
                </div>
            </div>
        </div>

		<div class='flex j-c mt5'>
			<div class='pers-maneken backgr2 flex j-c ai-c fl-di-co'>
				<div v-if='changed_elem' class='cost-elem-name flex j-c mt5'>
					<span class='mr5'>{{ elems[ changed_elem ] }}</span>
					<span>{{ this[changed_elem] }}</span>/<span>{{ this[changed_elem + `_max`] }}</span>
				</div>
				<div class='flex j-c ai-c pt5 pb5'>
					<div class='flex j-c fl-di-co'>
						<button class='cost-prevnext-btn mr5' @click='elemStyle("hair", "prev")'> ◄ </button>
						<button class='cost-prevnext-btn mt10 mr5' @click='elemStyle("beard", "prev")'> ◄ </button>
						<button class='cost-prevnext-btn mt10 mr5' @click='elemStyle("cloth", "prev")'> ◄ </button>
						<button class='cost-prevnext-btn mt10 mr5' @click='elemStyle("pants", "prev")'> ◄ </button>
						<button class='cost-prevnext-btn mt10 mr5' @click='elemStyle("fwear", "prev")'> ◄ </button>
					</div>

					<div class='maneken relative flex j-c ai-c'>
						<div :class='"hair" + hair'  id='hair'></div>
						<div :class='"beard" + beard' id='beard'></div>
						<div :class='"cloth" + cloth' id='cloth'></div>
						<div :class='"pants" + pants' id='pants'></div>
						<div :class='"fwear" + fwear' id='fwear'></div>

						<img class='man' src='/assets/man/man.png'>

						<div class='maneken-shadow'></div>
					</div>

					<div class='flex j-c fl-di-co'>
						<button class='cost-prevnext-btn ml5' @click='elemStyle("hair", "next")'> ► </button>
						<button class='cost-prevnext-btn mt10 ml5' @click='elemStyle("beard", "next")'> ► </button>
						<button class='cost-prevnext-btn mt10 ml5' @click='elemStyle("cloth", "next")'> ► </button>
						<button class='cost-prevnext-btn mt10 ml5' @click='elemStyle("pants", "next")'> ► </button>
						<button class='cost-prevnext-btn mt10 ml5' @click='elemStyle("fwear", "next")'> ► </button>
					</div>
				</div>
                <div class='flex j-c fl-di-co mt5 pb5'>
                    <button v-if='!ready' class='cost-ready-btn' @click='confirm("btns")'>Готово</button>
                    <div v-if='ready' class='flex j-c ai-c fnt15 mb5'>
                        Вы уверены?
                    </div>
                    <div v-if='ready' class='flex j-c'>
                        <button class='cost-confirm-btn mr10' @click='confirm("no")'>Нет</button>
                        <button class='cost-confirm-btn' @click='confirm("yes")'>Да</button>
                    </div>
                </div>
			</div>
		</div>
	</div>
</template>

<script>
module.exports = {
    name: 'Costumize',
    data: () => ({
    	hair: 1,
    	hair_max: 3,
    	beard: 1,
    	beard_max: 5,
    	cloth: 1,
    	cloth_max: 2,
    	pants: 1,
    	pants_max: 2,
    	fwear: 1,
    	fwear_max: 2,

        changed_elem: false,
        elems: {hair: 'Волосы', beard: 'Борода', cloth: 'Одежда', pants: 'Штаны', fwear: 'Обувь'},

    	ready: false
    }),
    methods:
    {
    	elemStyle(elem, move)
        {
    		switch(move)
            {
    			case 'next':
    				if (this[ elem ] + 1 <= this[ elem + '_max' ])
                    {
    					this[ elem ] += 1
    				}
    				break;
    			case 'prev':
    				if (this[ elem ] !== 1)
                    {
    					this[ elem ] -= 1
    				}
    				break;
    		}

            this.changed_elem = elem;
    	},
    	confirm(move)
        {
    		switch(move)
            {
    			case 'btns':
    				this.ready = true
    				break;
    			case 'no':
    				this.ready = false
    				break;
    			case 'yes':
    				this.costumize()
    				break;
    		}
    	},
    	costumize()
        {
    		let params = new FormData();
        	params.append('hair', this.hair);
        	params.append('beard', this.beard);
        	params.append('cloth', this.cloth);
        	params.append('pants', this.pants);
        	params.append('fwear', this.fwear);
        	params.append('token', localStorage.getItem('token'));

    		axios.post('/core/Costumize/', params)
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