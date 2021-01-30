<template>
	<button class='moves-btn flex j-s ai-c' @click='up'>
        <div class='game-btn-icon ml5 mr5 flex j-c ai-c'>
            <img src='/assets/icons/lvl.png' class='item14-1' />
        </div>
        <span v-if='move == 1'>Улучшить</span>
        <span v-else>Построить</span>
    </button>
</template>

<script>
module.exports = {
	name: 'UpRefuge',
	props: ['move'],
	methods: {
		up() {
			let params = new FormData();
	    	params.append('token', localStorage.getItem('token'));

			axios.post('/core/ajax/AllActions.php?action=uprefuge', params)
			.then((response) => {
				if (response.data.popup) {
					this.$root.popup.active = true;
					this.$root.popup.text   = response.data.message;
				} else if (response.data.page) {
					this.$router.push(response.data.page)
				} else if (response.data.reload) {
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