<template>
	<button
	 	v-if='move'
	 	@click='use'
		class='moves-btn flex j-s ai-c'>

        <div class='game-btn-icon ml5 mr5 flex j-c ai-c'>
            <img :src='`/front/assets/icons/`+ moves[ move ].icon +``' />
        </div>
        <span>{{ moves[ move ].word }}</span>
    </button>
</template>

<script>
module.exports = {
	name: 'Drink',
	props: ['move'],
	data: () => ({
		moves: {
			eat: {
				icon: 'hung.png',
				word: 'Есть'
			},
			drink: {
				icon: 'thirst.png',
				word: 'Пить'
			},
			nadet: {
				icon: 'power.png',
				word: 'Надеть'
			},
			read: {
				icon: 'lut.png',
				word: 'Читать'
			}
		}
	}),
	methods: {
		use()
		{
			this.$root.websockets.send(JSON.stringify({
				type: 'action',
				data: {action: this.move, item_id: this.$root.item.id}
			}))

			this.$root.changeModal('invent')
		}
	}
}
</script>