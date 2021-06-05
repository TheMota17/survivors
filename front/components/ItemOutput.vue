<template>
	<div class='flex'>
		<div
			v-for='(item, idx) in items'
			:class='(idx < items.length - 1) ? `mr5` : ``'
			class='flex j-c ai-c'>

	        <div
	        	@click='$root.openItemInfo(item)'
	        	:class='sysRares[ sysItems[ item[`type`] ][ item[`item`] ][`rare`] ][`border`]'
	        	class='item-link'>

	            <img :src='sysItems[ item[`type`] ][ item[`item`] ][`img`]' />
	        </div>
	    </div>
	</div>
</template>

<script>
module.exports = {
	name: 'ItemOutput',
	props: ['sysItems', 'sysRares', 'items']
}
</script>