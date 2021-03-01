<template>
	<div class='flex j-c'>
		<span class='flex mt5'>Страница не найдена</span>
	</div>
</template>

<script>
module.exports = {
	name: '404',
	beforeMount()
	{
		let params = new FormData();
    	params.append('token', localStorage.getItem('token'));

		axios.post('/core/Api/?page=404', params)
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
</script>