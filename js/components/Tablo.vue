<template>
	<div v-if='this.$route.path !== "/auth" && this.$route.path !== "/costumize"' class='hght25'>
	    <div class='info-table backgr2 flex j-c ai-c'>
	        
	        <div class='info-hp fl1 flex j-c ai-c'>
	            <img src='/assets/icons/hp.png' class='item14-1' alt='Жизнь' />
	            <span class='ml5 fnt14' id='hp'></span>
	        </div>
	        
	        <div class='info-hung fl1 flex j-c ai-c'>
	            <img src='/assets/icons/hung.png' class='item14-1' alt='Голод' />
	            <span class='ml5 fnt14' id='hung'></span>
	        </div>
	        
	        <div class='info-thirst fl1 flex j-c ai-c'>
	            <img src='/assets/icons/thirst.png' class='item14-1' alt='Жажда' />
	            <span class='ml5 fnt14' id='thirst'></span>
	        </div>
	        
	        <div class='info-fatigue fl1 flex j-c ai-c'>
	            <img src='/assets/icons/sleep.png' class='item14-1' alt='Истощение' />
	            <span class='ml5 fnt14' id='fatigue'></span>
	        </div>
	        
	    </div>
	</div>
</template>

<script>
module.exports = {
	name: 'Tablo'
}
</script>