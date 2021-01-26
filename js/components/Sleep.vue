<template>
	<div class='game-actions backgr2 flex j-c ai-c fl-di-co pt5 pb5'>
		<div v-if='active' class='flex j-c wdth86'>
            <button class='game-sleep-btn flex j-s ai-c' @click='deactive'>
                <div class='game-btn-icon ml5 mr5 flex j-c ai-c'>
                    <img src='/assets/icons/sleep.png' />
                </div>
                <span>Спать</span>
            </button>
        </div>

        <div v-if='!active' class='sleep-range flex j-c fl-di-co wdth86 mt10 pt5 pb5'>
            <div class='flex j-c'>
                <img class='mr5' src='/assets/icons/sleep.png' /> Спать: <div class='ml5'>{{ hours }}</div>ч.
            </div>
            <div class='flex j-c'>
                <input class='sleep-range-input mt10' type='range' min='1' max='24' step='1' value='1' v-model='hours'>
            </div>
            <div class='flex j-c mt10'>
                <button class='cost-ready-btn mr10' @click='activate'>Отмена</button>
                <button class='cost-ready-btn' @click='sleep'>Спать</button>
            </div>
        </div>
	</div>
</template>

<script>
module.exports = {
	name: 'Sleep',
    data: () => ({
        active: true,
        hours: 1
    }),
    methods: {
        activate() {
            this.active = true
        },
        deactive() {
            this.active = false
        },
        sleep() {
            console.log('sleep')
        }
    }
}
</script>