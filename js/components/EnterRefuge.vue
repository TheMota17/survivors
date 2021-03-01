<template>
	<button class='moves-btn flex j-s ai-c mt5' @click='enter'>
        <span class='flex j-c ai-c' v-if='userEnter == 0'>
        	<div class='game-btn-icon ml5 mr5 flex j-c ai-c'>
        		<img src='/assets/icons/enter.png' />
        	</div>
        	Войти
 		</span>
        <span class='flex j-c ai-c' v-else>
        	<div class='game-btn-icon ml5 mr5 flex j-c ai-c'>
        		<img src='/assets/icons/getout.png' />
        	</div>
        	Выйти
    	</span>
    </button>
</template>

<script>
module.exports = {
	name: 'EnterRefuge',
	props: ['userEnter'],
	methods:
	{
		enter()
		{
			let params = new FormData();
	    	params.append('token', localStorage.getItem('token'));

			axios.post('/core/GameActions/?action=enterrefuge', params)
			.then((response) => {
				if (response.data.popup)
				{
					this.$root.popup.active = true;
					this.$root.popup.text   = response.data.message;
				} else if (response.data.page)
				{
					this.$router.push(response.data.page)
				} else if (response.data.reload)
				{
					this.$router.go()
				}
			})
			.catch((error) => {
				console.log(error)
			})
		}
	}
}
</script>