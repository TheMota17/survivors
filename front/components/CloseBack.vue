<template>
	<div class='wdth100 close-back-btns flex j-sb mt5'>
		<button @click='close' class='close-close flex j-c ai-c'>
			<img src='/front/assets/icons/close.png' />
		</button>
		<button  @click='back' class='close-back flex j-c ai-c'>
			<img src='/front/assets/icons/back.png' />
		</button>
	</div>
</template>

<script>
module.exports = {
	name: 'CloseBack',
	methods:
	{
		close()
		{
			this.$root.gameModal.open = false;
			this.$root.closeAllModals(false);
		},
		back()
		{
			if (this.$root.gameModal.last == 'game')
			{
				this.close()
			} else
			{
				this.$root.gameModals[ this.$root.gameModal.last ] = true;
				this.$root.closeAllModals( this.$root.gameModal.last );
			}
		}
	}
}
</script>