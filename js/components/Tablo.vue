<template>
	<div v-if='$route.path !== "/auth" && $route.path !== "/costumize"' class='hght25'>
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
}
</script>