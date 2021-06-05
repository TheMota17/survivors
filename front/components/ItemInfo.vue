<template>
	<div class='flex j-c ai-c mt5'>
        <div class='wdth96 flex j-c ai-c fl-di-co'>
            <div class='iteminfo-div flex'>
                <div class='ml5'>
                    Тип:
                </div>
                <div class='fl1 flex j-e'>
                    <span class='mr5'>{{ sysItems[ type ][ item ][`type`] }}</span>
                </div>
            </div>

            <div
                v-for='(eff, key) in sysItems[ type ][ item ][`eff`]'
                v-if='sysItems[ type ][ item ][`eff`]'
                class='iteminfo-div wdth100 flex mt5'>

                <div class='ml5'>
                    <img :src='`/front/assets/icons/`+ key +`.png`' class='item14-1' />:
                </div>
                <div class='fl1 flex j-e'>
                    <span class='mr5'>{{ eff }}</span>
                </div>
            </div>

            <div v-if='sysItems[ type ][ item ][`dmgabs`]' class='iteminfo-div flex j-sb mt5'>
                <div class='ml5'>
                    <img src='/front/assets/icons/abs.png' class='item14-1' /> Подавление урона:
                </div>
                <div class='fl1 flex j-e'>
                    <span class='mr5'>
                        {{ sysItems[ type ][ item ][`dmgabs`] }}
                    </span>
                </div>
                <div v-if='nadeto && nadeto[ `type` + type ]'>
                    <img
                        v-if='sysItems[ type ][ item ][`dmgabs`] > sysItems[ type ][ nadeto[ `type` + type ] ][`dmgabs`]'
                        src='/front/assets/icons/better.png'
                        class='item14-1 mr5' />
                    <img
                        v-else-if='sysItems[ type ][ nadeto[`type` + type] ][`dmgabs`] == sysItems[ type ][ item ][`dmgabs`]'
                        src='/front/assets/icons/equally.png'
                        class='item14-1 mr5' />
                    <img
                        v-else
                        src='/front/assets/icons/worse.png'
                        class='item14-1 mr5' />
                </div>
            </div>

            <div v-else-if='sysItems[ type ][ item ][`dmgmin`]' class='iteminfo-div flex j-sb mt5'>
                <div class='ml5'>
                    <img src='/front/assets/icons/dmg.png' class='item14-1' /> Урон:
                </div>
                <div class='fl1 flex j-e'>
                    <span class='mr5'>
                        {{ sysItems[ type ][ item ][`dmgmin`] }}
                        -
                        {{ sysItems[ type ][ item ][`dmgmax`] }}
                    </span>
                </div>
                <div v-if='nadeto && nadeto[ `type` + type ]' class='flex ai-c'>
                    <img
                        v-if='sysItems[ type ][ item ][`dmgmin`] > sysItems[ type ][ nadeto[ `type` + type ] ][`dmgmin`]'
                        src='/front/assets/icons/better.png'
                        class='item14-1 mr5' />
                    <img
                        v-else-if='sysItems[ type ][ nadeto[`type` + type] ][`dmgmin`] == sysItems[ type ][ item ][`dmgmin`]'
                        src='/front/assets/icons/equally.png'
                        class='item14-1 mr5' />
                    <img
                        v-else
                        src='/front/assets/icons/worse.png'
                        class='item14-1 mr5' />
                </div>
            </div>

            <div v-if='sysItems[ type ][ item ][`radius`]' class='iteminfo-div flex j-sb mt5'>
                <div class='ml5'>
                    <img src='/front/assets/icons/radius.png' class='item14-1' /> Радиус атаки:
                </div>
                <div class='fl1 flex j-e'>
                    <span class='mr5'>
                        {{ sysItems[ type ][ item ][`radius`] }}
                    </span>
                </div>
                <div v-if='nadeto && nadeto[ `type` + type ]' class='flex ai-c'>
                    <img
                        v-if='sysItems[ type ][ item ][`radius`] > sysItems[ type ][ nadeto[ `type` + type ] ][`radius`]'
                        src='/front/assets/icons/better.png'
                        class='item14-1 mr5' />
                    <img
                        v-else-if='sysItems[ type ][ nadeto[`type` + type] ][`radius`] == sysItems[ type ][ item ][`radius`]'
                        src='/front/assets/icons/equally.png'
                        class='item14-1 mr5' />
                    <img
                        v-else
                        src='/front/assets/icons/worse.png'
                        class='item14-1 mr5' />
                </div>
            </div>

            <div v-if='sysItems[ type ][ item ][`power`]' class='iteminfo-div flex j-sb mt5'>
                <div class='ml5'>
                    <img src='/front/assets/icons/power.png' class='item14-1' /> Бонус к мощи:
                </div>
                <div class='fl1 flex j-e'>
                    <span class='mr5'>{{ sysItems[ type ][ item ][`power`] }}</span>
                </div>
                <div v-if='nadeto && nadeto[ `type` + type ]' class='flex ai-c'>
                    <img
                        v-if='sysItems[ type ][ item ][`power`] > sysItems[ type ][ nadeto[ `type` + type ] ][`power`]'
                        src='/front/assets/icons/better.png'
                        class='item14-1 mr5' />
                    <img
                        v-else-if='sysItems[ type ][ nadeto[`type` + type] ][`power`] == sysItems[ type ][ item ][`power`]'
                        src='/front/assets/icons/equally.png'
                        class='item14-1 mr5' />
                    <img
                        v-else
                        src='/front/assets/icons/worse.png'
                        class='item14-1 mr5' />
                </div>
            </div>

            <div v-if='colvo' class='iteminfo-div flex ai-c mt5'>
                <div class='flex ai-c'>
                    <img src='/front/assets/icons/colvo.png' class='item14-1 ml5 mr5' /> Количество:
                </div>
                <div class='fl1 flex j-e'>
                    <span class='mr5'>{{ colvo }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
module.exports = {
	name: 'ItemInfo',
	props: ['sysItems', 'item', 'type', 'colvo', 'nadeto']
}
</script>