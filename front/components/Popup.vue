<template>
	<div
        v-if='this.$root.popup.active'
        class='pop-up'>

      <div class='pop-up-body'>
	        <div class='pop-up-content'>
	            <div class='pop-up-maincontent txt-center'>{{ this.$root.popup.text }}</div>
	            <div class='flex j-c'>
	            	<button
                        @click='close'
                        class='close-btn'>Ок
                    </button>
	            </div>
	        </div>
	    </div>
	</div>
</template>

<script>
module.exports = {
	name: 'Popup',
	methods:
	{
		close()
		{
			this.$root.popup.active = false;
            this.$root.popup.checked = true;
		}
	}
}
</script>