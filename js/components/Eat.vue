<template>
	<div class='flex j-c mt10'>
        <div class='item-moves backgr2 flex j-c pt5 pb5'>
            <button class='moves-btn' @click='eat'>
                <span>Есть</span>
            </button>
        </div>
    </div>
</template>

<script>
module.exports = {
	name: 'Eat',
	methods:
	{
		eat()
		{
			let params = new FormData();
			params.append('id_item', this.$route.query.id);
	    	params.append('token', localStorage.getItem('token'));

			axios.post('/core/GameActions/?action=eat', params)
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