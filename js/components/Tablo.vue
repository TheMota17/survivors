<template>
	<div v-if='show || !tabloNotShowFrst' class='hght25'>
	    <div class='info-table backgr2 flex j-s ai-c'>

	        <div class='info-item flex j-c ai-c ml5'>
	            <img src='/assets/icons/hp.png' class='item14-1' alt='Жизнь' />
	            <span class='ml5 fnt14' id='hp'>{{ this.$root.tablo.hp }}</span>
	        </div>

	        <div class='info-item flex j-c ai-c ml10'>
	            <img src='/assets/icons/hung.png' class='item14-1' alt='Голод' />
	            <span class='ml5 fnt14' id='hung'>{{ this.$root.tablo.hung }}</span>
	        </div>

	        <div class='info-item flex j-c ai-c ml10'>
	            <img src='/assets/icons/thirst.png' class='item14-1' alt='Жажда' />
	            <span class='ml5 fnt14' id='thirst'>{{ this.$root.tablo.thirst }}</span>
	        </div>

	        <div class='info-item flex j-c ai-c ml10'>
	            <img src='/assets/icons/sleep.png' class='item14-1' alt='Истощение' />
	            <span class='ml5 fnt14' id='fatigue'>{{ this.$root.tablo.fatigue }}</span>
	        </div>

	    </div>
	</div>
</template>

<script>
module.exports = {
	name: 'Tablo',
	data: () => ({
		show: false,
		notWorkedPages: ['/auth', '/costumize']
	}),
	methods: {
		tabloNotShow(path)
		{
			for(let i = 0; i < this.notWorkedPages.length; i++)
			{
				if (this.notWorkedPages[i] == path) return true;
			}
		}
	},
	computed: {
		tabloNotShowFrst()
		{
			for(let i = 0; i < this.notWorkedPages.length; i++)
			{
				if (this.notWorkedPages[i] == window.location.pathname) return true;
			}
		}
	},
	watch: {
    	$route (to, from)
    	{
    		if (this.tabloNotShow(to))
    		{
    			this.show = false;
    		} else
    		{
    			this.show = true;
    		}
    	}
	}
}
</script>