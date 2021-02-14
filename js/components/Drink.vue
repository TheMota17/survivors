<template>
	<button class='moves-btn' @click='drink'>
        <span>Пить</span>
    </button>
</template>

<script>
module.exports = {
	name: 'Drink',
	methods: {
		drink() {
			let params = new FormData();
			params.append('id_item', this.$route.query.id);
	    	params.append('token', localStorage.getItem('token'));

			axios.post('/core/GameActions/?action=drink', params)
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