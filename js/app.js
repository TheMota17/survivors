(function() {

    let Popup   = httpVueLoader('./js/components/Popup.vue')
    let Tablo   = httpVueLoader('./js/components/Tablo.vue')
    let Menyoo  = httpVueLoader('./js/components/Menu.vue')
    let PageBar = httpVueLoader('./js/components/PageBar.vue')

    let Notf      = httpVueLoader('./js/views/404.vue')
    let Game      = httpVueLoader('./js/views/Game.vue')
    let Auth      = httpVueLoader('./js/views/Auth.vue')
    let Costumize = httpVueLoader('./js/views/Costumize.vue')

    const routes = [
        {
            path: '/',
            name: 'Game',
            component: Game
        }, {
            path: '/404',
            name: '404', 
            component: Notf,
        }, {
            path: '*',
            redirect: '/404' 
        }, {
            path: '/auth',
            name: 'Auth',
            component: Auth
        }, {
            path: '/costumize',
            name: 'Costumize',
            component: Costumize
        }
    ];

    const router = new VueRouter({
        mode: 'history',
        routes
    });

    new Vue({
        router,
        components: {
            Popup, Tablo, Menyoo, PageBar
        },
        data: () => ({
            popup: {
                text: 'Ошибка',
                active: false
            },
            pageBar: {
                width: 20
            }
        })
    }).$mount('#app')

})();