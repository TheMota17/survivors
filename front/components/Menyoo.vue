<template>
	<div>
		<div class='fl1 flex j-e ai-c mr10'>
			<svg @click='menuOpenClose' width='30' height='25' class='menu-btn'>
                <path d='M5,4.5 25,4.5'   stroke='#81664e' stroke-width='3' />
                <path d='M5,13 25,13'     stroke='#81664e' stroke-width='3' />
                <path d='M5,21.5 25,21.5' stroke='#81664e' stroke-width='3' />
            </svg>
	    </div>
		<div
			@click='menuOpenClose'
			:class='{menu_target_active: active}'
			class='menu-target' >
		</div>
		<div :class='{menu_active: active}' class='menu'>
		    <div class='menu-list pl5 pr5'>
		        <div class='menu-zag flex j-c fnt14 mt10'>Игра</div>
		        <div @click='$root.changeModal("game")' class='menu-a flex j-c ai-c mt10'>
		        	<span @click='menuOpenClose' class='menu-a'>
	        			<img src='/front/assets/icons/menu/game.png' class='item14-1 ml5 mr5'> Игра
	        		</span>
		        </div>
	            <div @click='$root.changeModal("invent")' class='menu-a flex j-c ai-c mt10'>
	            	<span @click='menuOpenClose' class='menu-a'>
	            		<img src='/front/assets/icons/menu/ivent.png' class='item14-1 ml5 mr5'> Инвентарь
	            	</span>
	            </div>
	            <div @click='$root.changeModal("craft")' class='menu-a flex j-c ai-c mt10'>
	            	<span @click='menuOpenClose' class='menu-a'>
	            		<img src='/front/assets/icons/menu/craft.png' class='item14-1 ml5 mr5'> Крафт
	            	</span>
	            </div>
		        <div class='menu-zag flex j-c fnt14 mt10'>Online</div>
	            <div class='menu-a flex j-c ai-c mt10'>
	            	<span @click='menuOpenClose' class='menu-a'>
	            		<img src='/front/assets/icons/menu/duel.png' class='item14-1 ml5 mr5'> Дуэль
	            	</span>
	            </div>
	            <div class='menu-a flex j-c ai-c mt10'>
	            	<span @click='menuOpenClose' class='menu-a'>
	            		<img src='/front/assets/icons/menu/shop.png' class='item14-1 ml5 mr5'> Магазин
	            	</span>
	            </div>
	            <div class='menu-a flex j-c ai-c mt10'>
	            	<span @click='menuOpenClose' class='menu-a'>
	            		<img src='/front/assets/icons/menu/chat.png' class='item14-1 ml5 mr5'> Общий чат
	            	</span>
	            </div>
		        <div class='menu-zag flex j-c fnt14 mt10'>Система</div>
	            <div class='menu-a flex j-c ai-c mt10'>
	            	<span @click='menuOpenClose' class='menu-a'>
	            		<img src='/front/assets/icons/menu/settings.png' class='item14-1 ml5 mr5'> Настройки
	            	</span>
	            </div>
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
		menuOpenClose()
		{
			this.active = !this.active
		}
	}
}
</script>