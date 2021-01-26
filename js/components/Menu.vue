<template>
	<div v-if='$route.path !== "/auth" && $route.path !== "/costumize"'>
		<div class='fl1 flex j-e ai-c mr10'>
			<a class='menu-btn' @click='openclose'></a>
	    </div>
		<div class='menu-target' :class='{menu_target_active: active}' @click='openclose'></div>
		<div class='menu relative' :class='{menu_active: active}'>
		    <div class='menu-list pl5 pr5'>
		        <div class='menu-zag flex j-c fnt14 mt10'>Игра</div>
	        	<router-link to='/' class='menu-a flex j-c ai-c mt10'>
	        		<span class='menu-a' @click='openclose'>
	        			<img src='/assets/icons/menu/game.png' class='item14-1 ml5 mr5'> Игра
	        		</span>
	        	</router-link>
	            <router-link to='/invent' class='menu-a flex j-c ai-c mt10'>
	            	<span class='menu-a' @click='openclose'>
	            		<img src='/assets/icons/menu/ivent.png' class='item14-1 ml5 mr5'> Инвентарь
	            	</span>
	            </router-link>
	            <router-link to='/craft' class='menu-a flex j-c ai-c mt10'>
	            	<span class='menu-a' @click='openclose'>
	            		<img src='/assets/icons/menu/craft.png' class='item14-1 ml5 mr5'> Крафт
	            	</span>
	            </router-link>
	            <router-link to='/refuge' class='menu-a flex j-c ai-c mt10'>
	            	<span class='menu-a' @click='openclose'>
	            		<img src='/assets/icons/menu/refuge.png' class='item14-1 ml5 mr5'> Убежище
	            	</span>
	            </router-link>
		        <div class='menu-zag flex j-c fnt14 mt10'>Online</div>
	            <router-link to='/duel' class='menu-a flex j-c ai-c mt10'>
	            	<span class='menu-a' @click='openclose'>
	            		<img src='/assets/icons/menu/duel.png' class='item14-1 ml5 mr5'> Дуэль
	            	</span>
	            </router-link>
	            <router-link to='/shop' class='menu-a flex j-c ai-c mt10'>
	            	<span class='menu-a' @click='openclose'>
	            		<img src='/assets/icons/menu/shop.png' class='item14-1 ml5 mr5'> Магазин
	            	</span>
	            </router-link>
	            <router-link to='/chat' class='menu-a flex j-c ai-c mt10'>
	            	<span class='menu-a flex j-c ai-c mt10' @click='openclose'>
	            		<img src='/assets/icons/menu/chat.png' class='item14-1 ml5 mr5'> Общий чат
	            	</span>
	            </router-link>
		        <div class='menu-zag flex j-c fnt14 mt10'>Система</div>
	            <router-link to='/settings' class='menu-a flex j-c ai-c mt10'>
	            	<span class='menu-a' @click='openclose'>
	            		<img src='/assets/icons/menu/settings.png' class='item14-1 ml5 mr5'> Настройки
	            	</span>
	            </router-link>
		    </div>
		</div>
	</div>
</template>

<script>
module.exports = {
	name: 'Menyoo',
	data: () => ({
		active: false
	}),
	methods: {
		openclose() {
			this.active = !this.active
		}
	}
}
</script>