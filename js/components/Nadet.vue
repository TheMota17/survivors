<template>
	<button class='moves-btn' @click='nadet'>
        <span>Надеть</span>
    </button>
</template>

<script>
module.exports = {
	name: 'Nadet',
	methods:
	{
		nadet()
		{
			let params = new FormData();
			params.append('id_item', this.$route.query.id);
	    	params.append('token', localStorage.getItem('token'));

			axios.post('/core/GameActions/?action=nadet', params)
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
					this.$router.go();
				}
			})
			.catch((error) => {
				console.log(error)
			})
		}
	}
}
</script>