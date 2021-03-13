<template>
	<div class='flex'>
		<div v-for='item in items' class='item32-1 mr5 flex j-c ai-c'>
	        <router-link :to='{ path: `item`, query: {item: item[`item`], type: item[`type`]}}' class='item-link' :class='sysRares[ sysItems[ item[`type`] ][ item[`item`] ][`rare`] ][`border`]'>
	            <img :src='sysItems[ item[`type`] ][ item[`item`] ][`img`]'/>
	        </router-link>
	    </div>
	</div>
</template>

<script>
module.exports = {
	name: 'ItemOutput',
	props: ['items', 'sysItems', 'sysRares']
}
</script>