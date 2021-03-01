<template>
	<div v-if='api'>
	    <tablo :hp='game.hp' :hung='game.hung' :thirst='game.thirst' :fatigue='game.fatigue'></tablo>
        <div class='flex j-c mt5'>
            Общий чат
        </div>
        <div class='flex j-c mt5'>
            <div class='chat-form wdth96 flex j-c pt5 pb5'>
                <input type='text' class='chat-input' placeholder='Ваше сообщение' v-model='inputText' cols='10'>
                <button class='chat-btn ml5' @click='sendMessage'>
                    <img src='/assets/icons/send.png' />
                </button>
            </div>
        </div>
        <div class='flex j-c mt5'>
            <div class='chat-body wdth96 flex j-s fl-di-co ai-c pb5'>
                <div v-for='mess in paginatedData' class='chat-item mt5'>
                    <div class='ml5'>
                        <span class='mess'>{{ mess.login }}: </span>
                        <span>{{ mess.mess }}</span>
                        <span class='mess-time ml5'>{{ Math.floor(Date.now() / 1000) - mess.time }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class='flex j-c mt5'>
            <div class='chat-nav backgr2 flex j-c mt5 pt5 pb5'>
                <div class='wdth96 flex j-sb'>
                    <button :disabled='page == 1' @click='prevPage' class='nav-btn ml5'>◄</button>
                    <span class='nav-btn'>{{ page }}</span>
                    <button :disabled='page >= pageCount' @click='nextPage' class='nav-btn mr5'>►</button>
                </div>
            </div>
        </div>
	</div>
</template>

<script>
let date  = Date.now();
let Tablo = httpVueLoader('../components/Tablo.vue?_='+date)

module.exports = {
    name: 'Chat',
    data: () => ({
    	api: false,

        messages: [],
        inputText: '',
        page: 1,
        maxmess: 10,

        game: undefined,
    }),
    components:
    {
        Tablo
    },
    beforeMount()
    {
        let params = new FormData();
        params.append('token', localStorage.getItem('token'));

        axios.post('/core/Api/?page=chat', params)
        .then((response) => {
            if (response.data.popup)
            {
                this.$root.popup.active = true;
                this.$root.popup.text   = response.data.message;
            } else if (response.data.page)
            {
                this.$router.push(response.data.page)
            } else
            {
                this.game     = response.data.game;
                this.messages = response.data.messages;

                this.api = true;
            }
        })
        .catch((error) => {
            console.log(error)
        })
    },
    methods:
    {
        nextPage()
        {
            this.page += 1;
        },
        prevPage()
        {
            this.page -= 1;
        },
        sendMessage()
        {
            this.messages.push({
                nick: 'Admin',
                time: '5 сек',
                mess: this.inputText
            })
        }
    },
    computed:
    {
        pageCount()
        {
            let l = this.messages.length, s = this.maxmess;
            return Math.ceil(l/s);
        },
        paginatedData()
        {
            const start = (this.page - 1) * this.maxmess, end = start + this.maxmess;
            return this.messages.slice(start, end);
        }
    }
}
</script>