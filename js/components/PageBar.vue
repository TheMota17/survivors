<template>
	<div v-if='false' class='page-load-bar'>
    	<div class='load-bar' :style='{width: this.$root.pageBar.width + `%`}'></div>
	</div>
</template>

<script>
module.exports = {
	name: 'PageBar'
}
</script>