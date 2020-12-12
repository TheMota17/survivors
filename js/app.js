let pageLoad = {
    data: {
        'routes': ['/panel', '/auth', '/costumize', '/game', '/ivent', '/item', '/craft', '/refuge'],
        'token': 0
    },
    methods: {
        token: function() {
            pageLoad.data['token'] = $('#token').val();
        },
        events: function() {
            // Обработка клика на ссылки
            $('body').on('click', 'a.ajax', function(e) {
                e.preventDefault();
                pageLoad.model('loadPage', $(this).attr('href'));
            });
            // Обработка клика на кнопки назад, вперед
            window.onpopstate = function(e) {
                let page = (e.state && e.state.page) || 'game';
                pageLoad.model('loadPage', page);
            }
            // Проверка на перезагрузку страницы
            if (performance.navigation.type == 1) {
                if (pageLoad.methods.routeAvai(window.location.pathname)) {
                    pageLoad.model('loadPage', window.location.pathname + window.location.search);
                }
            } else {
                pageLoad.model('loadPage', '/auth');
            }
        },
        pushState: function(pageFormat) {
            // Сохранение страницы в истории
            let state = {
                page: pageFormat['page'] + pageFormat['get']
            }
            window.history.pushState(state, '', state.page);
        },
        pageFormat: function(page) {
            let path = page.split('?')[0];
            let get = page.split('?')[1];

            if (!get) get = '';
            else get = '?' + get;

            return {
                'page': path,
                'get': get
            };
        },
        specMoves: function(pageFormat) {
            menu.start();
            menu.methods.openclose('close');
            menu.methods.hide(pageFormat['page']);
        },
        getTitle: function(pageFormat) {
            switch (pageFormat['page']) {
                case '/auth':
                    return 'Survivors';
                    break;
                case '/costumize':
                    return 'Настроить персонажа';
                    break;
                case '/game':
                    return 'Главная';
                    break;
                case '/ivent':
                    return 'Инвентарь';
                    break;
                case '/item':
                    return 'Предмет';
                    break;
                case '/craft':
                    return 'Крафт';
                    break;
                case '/refuge':
                    return 'Убежище';
                    break;
            }
        },
        routeAvai: function(page) {
            // Проверка на допустимость роута
            for (let i = 0; i < pageLoad.data['routes'].length; i++) {
                if (pageLoad.data['routes'][i] == page) return true;
            }
        }
    },
    model: function(move, page) {
        switch (move) {
            case 'loadPage':
                pageLoad.view('barnone', 0, 0);

                pageFormat = pageLoad.methods.pageFormat(page);

                if (pageLoad.methods.routeAvai(pageFormat['page'])) {
                    $.ajax({
                        url: pageFormat['page'] + '.php' + pageFormat['get'],
                        method: 'POST',
                        data: {
                            token: pageLoad.data['token']
                        },
                        success: function(data) {
                            pageLoad.view('baractivate', 0, 0);
                            pageLoad.view('pagerender', data, pageFormat);

                            pageLoad.methods.pushState(pageFormat);
                            pageLoad.methods.specMoves(pageFormat);
                        }
                    });
                }
                break;
        }
    },
    view: function(move, data, pageFormat) {
        switch (move) {
            case 'pagerender':
                $('#container').html(data);
                $('title').html(pageLoad.methods.getTitle(pageFormat));
                $('#upd-page').attr('href', pageFormat['page'] + pageFormat['get']);
                break;
            case 'barnone':
                $('#load_bar').css('width', 20 + '%');
                $('#page_load_bar').removeClass('none');
                break;
            case 'baractivate':
                $('#load_bar').css('width', 100 + '%');
                setTimeout(function() {
                    $('#page_load_bar').addClass('none');
                }, 700);
                break;
        }
    },
    start: function() {
        pageLoad.methods.token();
        pageLoad.methods.events();
    }
};

let auth = {
    data: {
        'workpath': '/auth'
    },
    methods: {
        messHand: function(data) {
            if (data['dom_data']) {
                this.view(data);
            } else if (data['popup'] && data['reload']) {
                popup.methods.activate(data['message']);
                pageLoad.model('loadPage', window.location.pathname + window.location.search);
            } else if (data['popup']) {
                popup.methods.activate(data['message']);
            } else if (data['page']) {
                pageLoad.model('loadPage', data['page']);
            } else if (data['reload']) {
                pageLoad.model('loadPage', window.location.pathname + window.location.search);
            }
        },
        pageAvai: function() {
            if (auth.data['workpath'] === window.location.pathname) return true;
        },
        events: function() {
            $('body').on('click', function(e) {
                switch (e.target.id) {
                    case 'enter':
                        if ($('#enter-name').val() == '' || $('#enter-pass').val() == '')
                            popup.methods.activate('Заполните поля!');
                        else
                            auth.model('enter');
                        break;
                    case 'reg':
                        if ($('#reg-name').val() == '' || $('#reg-pass').val() == '' || $('#reg-mail').val() == '')
                            popup.methods.activate('Заполните поля!');
                        else
                            auth.model('reg');
                        break;
                }
            });
        }
    },
    model: function(move) {
        if (auth.methods.pageAvai()) {

            let data;
            switch (move) {
                case 'reg':
                    data = {
                        reg: true,
                        reg_name: $('#reg-name').val(),
                        reg_pass: $('#reg-pass').val(),
                        reg_mail: $('#reg-mail').val(),
                        token: pageLoad.data['token']
                    };
                    break;
                case 'enter':
                    data = {
                        enter: true,
                        enter_name: $('#enter-name').val(),
                        enter_pass: $('#enter-pass').val(),
                        token: pageLoad.data['token']
                    };
                    break;
            }

            $.ajax({
                url: 'core/ajax/auth.php',
                type: 'POST',
                data: data,
                success: function(data) {
                    if (data) {
                        data = JSON.parse(data);
                        auth.methods.messHand(data);
                    }
                }
            });
        }
    },
    view: function() {
        //
    },
    start: function() {
        this.methods.events();
    }
};

let costumize = {
    data: {
        'workpath': '/costumize',
        'styles': {
            'hair': 1,
            'maxhair': 3,

            'beard': 1,
            'maxbeard': 5,

            'cloth': 1,
            'maxcloth': 2,

            'pants': 1,
            'maxpants': 2,

            'fwear': 1,
            'maxfwear': 1
        }
    },
    methods: {
        messHand: function(data) {
            if (data['dom_data']) {
                this.view(data);
            } else if (data['popup'] && data['reload']) {
                popup.methods.activate(data['message']);
                pageLoad.model('loadPage', window.location.pathname + window.location.search);
            } else if (data['popup']) {
                popup.methods.activate(data['message']);
            } else if (data['page']) {
                pageLoad.model('loadPage', data['page']);
            } else if (data['reload']) {
                pageLoad.model('loadPage', window.location.pathname + window.location.search);
            }
        },
        pageAvai: function() {
            if (costumize.data['workpath'] === window.location.pathname) return true;
        },
        events: function() {
            $('body').on('click', function(e) {
                switch (e.target.id) {
                    case 'ready':
                        costumize.methods.ready();
                        break;
                    case 'costumize_no':
                        costumize.methods.noready();
                        break;
                    case 'costumize_yes':
                        costumize.model('saveSett');
                        break;
                    default:
                        let move      = e.target.id.split('-')[0];
                        let styleElem = e.target.id.split('-')[1];
                        costumize.methods.costumize(move, styleElem);
                        break;
                }
            });
        },
        costumize: function(move, styleElem) {
            switch (move) {
                case 'prev':
                    if (costumize.data['styles'][styleElem] !== 1)
                        costumize.data['styles'][styleElem] -= 1;
                    break;
                case 'next':
                    if (costumize.data['styles']['max' + styleElem] !== costumize.data['styles'][styleElem])
                        costumize.data['styles'][styleElem] += 1;
                    break;
            }
            costumize.view(styleElem);
        },
        elemData: function(move, elem) {
            switch(move) {
                case 'colvo':
                    return costumize.data['styles'][ elem ];
                break;
                case 'max':
                    return costumize.data['styles'][ 'max' + elem ];
                break;
                case 'name':
                    switch(elem) {
                        case 'hair':  return 'Волосы'; break;
                        case 'beard': return 'Борода'; break;
                        case 'cloth': return 'Вверх'; break;
                        case 'pants': return 'Низ'; break;
                        case 'fwear': return 'Обувь'; break;
                    }
                break;
            }
        },
        ready: function() {
            $('#ready-hide').addClass('none');
            $('#confirm-hide').removeClass('none');
        },
        noready: function() {
            $('#ready-hide').removeClass('none');
            $('#confirm-hide').addClass('none');
        }
    },
    model: function(move) {
        if (costumize.methods.pageAvai()) {
            switch (move) {
                case 'saveSett':
                    $.ajax({
                        url: 'core/ajax/savesett.php',
                        method: 'POST',
                        data: {
                            save_sett: true,
                            hair: costumize.data.styles['hair'],
                            beard: costumize.data.styles['beard'],
                            cloth: costumize.data.styles['cloth'],
                            pants: costumize.data.styles['pants'],
                            fwear: costumize.data.styles['fwear'],
                            token: pageLoad.data['token']
                        },
                        success: function(data) {
                            if (data) {
                                data = JSON.parse(data);
                                costumize.methods.messHand(data);
                            }
                        }
                    });
                    break;
            }
        }
    },
    view: function(styleElem) {
        $('#style_elem_name').removeClass('none');
        $('#elem_name').html( costumize.methods.elemData('name', styleElem) );
        $('#elem_colvo').html( costumize.methods.elemData('colvo', styleElem) );
        $('#elem_max').html( costumize.methods.elemData('max', styleElem) );

        $('#' + styleElem).removeClass();
        $('#' + styleElem).addClass(styleElem + costumize.data['styles'][styleElem]);

        let arr = ['hair', 'beard', 'cloth', 'pants', 'fwear'];

        for (let i = 0; i < arr.length; i++) {

            $('#' + arr[i] + '-colvo').html(
                costumize.data['styles'][arr[i]]
            );
            $('#' + arr[i] + '-max').html(
                costumize.data['styles']['max' + arr[i]]
            );

        }
    },
    start: function() {
        this.methods.events();
    }
};

let game = {
    data: {
        'workpath': ['/game', '/item', '/craft'],
        'actions': ['srchloc', 'srchlut', 'eat', 'drink', 'sleep', 'nadet', 'craft', 'read'],
        'interval_time': 1000,
        'sleep_time': 1,
    },
    methods: {
        messHand: function(data) {
            if (data['dom_data']) {
                this.view(data);
            } else if (data['popup'] && data['reload']) {
                popup.methods.activate(data['message']);
                pageLoad.model('loadPage', window.location.pathname + window.location.search);
            } else if (data['popup']) {
                popup.methods.activate(data['message']);
            } else if (data['page']) {
                pageLoad.model('loadPage', data['page']);
            } else if (data['reload']) {
                pageLoad.model('loadPage', window.location.pathname + window.location.search);
            }
        },
        pageAvai: function() {
            for (let i = 0; i < game.data['workpath'].length; i++) {
                if (game.data['workpath'][i] === window.location.pathname) return true;
            }
        },
        events: function() {
            $('body').on('change', function(e) {
                if (e.target.id == 'sleep_range_input') {
                    game.data['sleep_time'] = $('#' + e.target.id).val();
                    game.view('sleep_time');
                }
            });
            $('body').on('click', function(e) {
                switch (e.target.id) {
                    case 'sleep_no':
                        game.view('sleep_no');
                        break;
                    case 'sleep_yes':
                        game.methods.actionSend('sleep');
                        break;
                    case 'sleep':
                        game.view('sleep_range');
                        break;
                    default:
                        if (game.methods.actionAvai(e.target.id)) barsMove.methods.activate(e.target.id);
                        break;
                }
            });
        },
        actionSend: function(action) {
            let data;
            switch(action) {
                case 'srchloc':
                    data = {
                        from: window.location.pathname.split('/')[1],
                        token: pageLoad.data['token']
                    };
                    break;
                case 'srchlut':
                    data = {
                        from: window.location.pathname.split('/')[1],
                        token: pageLoad.data['token']
                    };
                    break;
                case 'eat':
                    let eat = new URLSearchParams(window.location.search);
                    data = {
                        id_item: eat.get('id'),
                        from: window.location.pathname.split('/')[1],
                        token: pageLoad.data['token']
                    };
                    break;
                case 'drink':
                    let drink = new URLSearchParams(window.location.search);
                    data = {
                        id_item: drink.get('id'),
                        from: window.location.pathname.split('/')[1],
                        token: pageLoad.data['token']
                    };
                    break;
                case 'sleep':
                    data = {
                        hours: game.data['sleep_time'],
                        from: window.location.pathname.split('/')[1],
                        token: pageLoad.data['token']
                    };
                    break;
                case 'nadet':
                    let nadet = new URLSearchParams(window.location.search);
                    data = {
                        id_item: nadet.get('id'),
                        from: window.location.pathname.split('/')[1],
                        token: pageLoad.data['token']
                    };
                    break;
                case 'craft':
                    let crafting = new URLSearchParams(window.location.search);
                    data = {
                        id: crafting.get('id'),
                        item: crafting.get('item'),
                        type: crafting.get('type'),
                        colvo: craft.data['colvo'],
                        from: window.location.pathname.split('/')[1],
                        token: pageLoad.data['token']
                    };
                    break;
                case 'read':
                    let read = new URLSearchParams(window.location.search);
                    data = {
                        id_item: read.get('id'),
                        from: window.location.pathname.split('/')[1],
                        token: pageLoad.data['token']
                    };
                break;
            }
            game.model('gameacts', action, data);
        },
        actionAvai: function(action) {
            for (let i = 0; i < game.data['actions'].length; i++) {
                if (game.data['actions'][i] == action) return true;
            }
        }
    },
    model: function(move, action, data) {
        if (game.methods.pageAvai()) {
            switch (move) {
                case 'gameacts':
                    $.ajax({
                        url: 'core/ajax/gameactions.php?action=' + action,
                        data: data,
                        method: 'POST',
                        success: function(data) {
                            if (data) {
                                data = JSON.parse(data);
                                game.methods.messHand(data);
                            }
                        }
                    });
                    break;
            }
        }
    },
    view: function(move, data) {
        switch (move) {
            case 'sleep_range':
                $('#sleep_button').addClass('none');
                $('#sleep_range').removeClass('none');
                break;
            case 'sleep_no':
                $('#sleep_button').removeClass('none');
                $('#sleep_range').addClass('none');
                break;
            case 'sleep_time':
                $('#sleep_time').html(game.data['sleep_time']);
                break;
        }
    },
    start: function() {
        this.methods.events();
    }
};

let craft = {
    data: {
        'workpath': '/craft',
        'colvo': 1,
    },
    methods: {
        messHand: function(data) {
            if (data['dom_data']) {
                this.view(data);
            } else if (data['popup'] && data['reload']) {
                popup.methods.activate(data['message']);
                pageLoad.model('loadPage', window.location.pathname + window.location.search);
            } else if (data['popup']) {
                popup.methods.activate(data['message']);
            } else if (data['page']) {
                pageLoad.model('loadPage', data['page']);
            } else if (data['reload']) {
                pageLoad.model('loadPage', window.location.pathname + window.location.search);
            }
        },
        pageAvai: function() {
            if (craft.data['workpath'] === window.location.pathname) return true;
        },
        events: function() {
            $('body').on('change', function(e) {
                if (e.target.id == 'colvo_range_input') {
                    craft.data['colvo'] = $('#' + e.target.id).val();
                    craft.view('colvo');
                }
            });
        }
    },
    model: function() {
        //
    },
    view: function(move, data) {
        switch(move) {
            case 'colvo':
                $('#colvo_craft').html(craft.data['colvo']);
            break;
        }
    },
    start: function() {
        this.methods.events();
    }
};

let main = {
    start: function() {
        popup.start();

        pageLoad.start();
        auth.start();
        costumize.start();
        game.start();
        craft.start();
    }
};
main.start();