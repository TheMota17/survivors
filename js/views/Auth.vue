<template>
	<div v-if='api' class='flex j-c ai-c fl-di-co'>
	    <div class='auth-logo'>
	        <img src='/assets/auth.png' class='logo'>
	        <div class='auth-info wdth100 flex j-c ai-e mt5 bolder'>
	        	Исследуйте, стройте, уничтожайте в игре - которая перевернет все ваши стереотипы о Выживших!
	    	</div>
	    </div>
	    <div class='auth-reg flex j-c ai-c fl-di-co pb10'>
	        <div class='auth-reg-zag flex j-c ai-c bolder fnt12 mt5'>
	            Начать путь
	        </div>
	        <div class='auth-reg-form flex ai-c fl-di-co mt10'>
	            <div class='relative'>
	                <div class='error-star' v-if='!regData.name'>*</div>
	                <input type='text' placeholder='Придумайте ник' class='input' v-model='regData.name' required>
	            </div>

	    	    <div class='relative mt10'>
	                <div class='error-star' v-if='!regData.pass'>*</div>
	    		    <input type='password' placeholder='Придумайте пароль' class='input' v-model='regData.pass' required>
	    	    </div>

	            <div class='relative mt10'>
	                <div class='error-star' v-if='!regData.mail'>*</div>
	                <input type='mail' placeholder='Ваша почта' class='input' v-model='regData.mail' required>
	            </div>

                <div class='bolder mt5'>
                    {{ authToken }}
                </div>
                <div class='relative mt5'>
                    <div class='error-star' v-if='!regData.authToken'>*</div>
                    <input type='text' placeholder='Проверочный код' class='input' v-model='regData.authToken' required>
                </div>

	            <div class='mt10'>
	                <button type='submit' class='input button' @click='reg'>Далее</button>
	            </div>
	        </div>
	    </div>

	    <div class='auth-enter flex j-c ai-c fl-di-co'>
	        <div class='auth-enter-zag flex j-c ai-c bolder fnt12 mt5'>
	            Войти
	        </div>
	        <div class='auth-enter-form flex ai-c fl-di-co pb5'>
	            <div class='mt10'>
	            <input type='text' placeholder='Ник' class='input' v-model='enterData.name' required>
	            </div>

	            <div class='mt10'>
	            <input type='password' placeholder='Пароль' class='input' v-model='enterData.pass' required>
	            </div>

	            <div class='mt10'>
	            <button name='enter' class='input button' @click='enter'>Войти</button>
	            </div>
	            
	            <div class='mt5'>
	            <a href='#' class='bottom-line fnt12'>Забыли пароль?</a>
	            </div>
	        </div>
	    </div>
	</div>
</template>

<script>
module.exports = {
    name: 'Auth',
    data: () => ({
        api: false,

        authToken: undefined,
    	regData: {
    		name: undefined,
    		pass: undefined,
    		mail: undefined,
            authToken: undefined
    	},
    	enterData: {
    		name: undefined,
    		pass: undefined
    	}
    }),
    beforeMount() {
        let params = new FormData();
        params.append('token', localStorage.getItem('token'));

        axios.post('/core/Api/?page=auth', params)
        .then((response) => {
            if (response.data.popup) {
                this.$root.popup.active = true;
                this.$root.popup.text   = response.data.message;
            } else if (response.data.page) {
                this.$router.push(response.data.page)
            } else {
                this.authToken = response.data.authToken;

                this.api = true;
            }
        })
        .catch((error) => {
            console.log(error)
        })
    },
    methods: {
    	reg() {
    		let params = new FormData();
        	params.append('name', this.regData.name);
        	params.append('pass', this.regData.pass);
        	params.append('mail', this.regData.mail);
            params.append('authToken', this.regData.authToken);

    		axios.post('/core/Auth/?action=reg', params)
    		.then((response) => {
    			if (response.data.popup) {
    				this.$root.popup.active = true;
    				this.$root.popup.text   = response.data.message;
    			} else if (response.data.page) {
    				localStorage.setItem('token', response.data.token)
    				this.$router.push(response.data.page)
    			}
    		})
    		.catch((error) => {
    			console.log(error)
    		})
    	},
    	enter() {
    		let params = new FormData();
        	params.append('name', this.enterData.name);
        	params.append('pass', this.enterData.pass);

    		axios.post('/core/Auth/?action=enter', params)
    		.then((response) => {
    			if (response.data.popup) {
    				this.$root.popup.active = true;
    				this.$root.popup.text   = response.data.message;
    			} else if (response.data.page) {
    				localStorage.setItem('token', response.data.token)
    				this.$router.push(response.data.page)
    			}
    		})
    		.catch((error) => {
    			console.log(error)
    		})
    	}
    }
}
</script>