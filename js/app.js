let PageLoad = {
    construct() {
        this.token  = $('#token').val();
        this.routes = ['/panel', '/auth', '/costumize', '/game', '/ivent', '/item', '/craft', '/refuge'];
    },

    pushState(pageFormat) {
        let state = {page: pageFormat['page'] + pageFormat['get']};
        window.history.pushState(state, '', state.page);
    },

    pageFormat(page) {
        let path = page.split('?')[0];
        let get  = page.split('?')[1];
        
        if (!get) get = '';
        else get = '?' + get;

        return {'page': path, 'get': get};
    },

    routeAvai(page) {
        for (let i = 0; i < this.routes.length; i++) {
            if (this.routes[i] == page) return true;
        }
    },

    specMoves(pageFormat) {
        Menu.openclose('close');
    },

    title(pageFormat) {
        switch (pageFormat['page']) {
            case '/auth':return 'Survivors';break;
            case '/costumize':return 'Настроить персонажа';break;
            case '/game':return 'Главная';break;
            case '/ivent':return 'Инвентарь';break;
            case '/item':return 'Предмет';break;
            case '/craft':return 'Крафт';break;
            case '/refuge':return 'Убежище';break;
        }
    },

    loadPage(page) {
        this.view('barnone');
        let pageFormat = this.pageFormat(page);

        if (this.routeAvai(pageFormat['page'])) {
            $.ajax({
                url: pageFormat['page'] + '.php' + pageFormat['get'],
                method: 'POST',
                data: {
                    token: this.token
                },
                success: function(data) {
                    PageLoad.view('baractivate');
                    PageLoad.view('page', data, pageFormat);

                    PageLoad.pushState(pageFormat);
                    PageLoad.specMoves(pageFormat);
                }
            });
        }
    },

    view(move, data, pageFormat) {
        switch (move) {
            case 'page':
                $('#container').html(data);
                $('title').html(this.title(pageFormat));
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
};

let Auth = {
    construct() {
        this.workpath = '/auth';
    },

    messHandler(data) {
        if (data['dom_data']) {
            this.view(data);
        } else if (data['popup'] && data['reload']) {
            Popup.activate(data['message']);
            PageLoad.loadPage(window.location.pathname + window.location.search);
        } else if (data['popup']) {
            Popup.activate(data['message']);
        } else if (data['page']) {
            PageLoad.loadPage(data['page']);
        } else if (data['reload']) {
            PageLoad.loadPage(window.location.pathname + window.location.search);
        }  
    },

    pageAvai() {
        if (this.workpath === window.location.pathname) return true;
    },

    reg() {
        if (this.pageAvai()) {
            let data = {
                reg: true,
                reg_name: $('#reg-name').val(),
                reg_pass: $('#reg-pass').val(),
                reg_mail: $('#reg-mail').val(),
                token: PageLoad.token
            };

            this.auth(data);
        }
    },

    enter() {
        if (this.pageAvai()) {
            let data = {
                enter: true,
                enter_name: $('#enter-name').val(),
                enter_pass: $('#enter-pass').val(),
                token: PageLoad.token
            };

            this.auth(data);
        }
    },

    auth(data) {
        $.ajax({
            url: 'core/ajax/auth.php',
            type: 'POST',
            data: data,
            success: function(data) {
                if (data) {
                    data = JSON.parse(data);
                    Auth.messHandler(data);
                }
            }
        });
    }
};

let Costumize = {
    construct() {
        this.workpath = '/costumize';
        this.hair = 1;
        this.maxhair = 3;
        this.beard = 1;
        this.maxbeard = 5;
        this.cloth = 1;
        this.maxcloth = 2;
        this.pants = 1;
        this.maxpants = 2;
        this.fwear = 1;
        this.maxfwear = 1;
    },

    messHandler(data) {
        if (data['dom_data']) {
            this.view(data);
        } else if (data['popup'] && data['reload']) {
            Popup.activate(data['message']);
            PageLoad.loadPage(window.location.pathname + window.location.search);
        } else if (data['popup']) {
            Popup.activate(data['message']);
        } else if (data['page']) {
            PageLoad.loadPage(data['page']);
        } else if (data['reload']) {
           PageLoad.loadPage(window.location.pathname + window.location.search);
        }  
    },

    pageAvai() {
        if (this.workpath === window.location.pathname) return true;
    },

    costumize(move, styleElem) {
        switch (move) {
            case 'prev':
                if (this[styleElem] !== 1) this[styleElem] -= 1;
                break;
            case 'next':
                if (this['max' + styleElem] !== this[styleElem]) this[styleElem] += 1;
                break;
        }
        this.view(styleElem);
    },

    elemData(move, elem) {
        switch(move) {
            case 'colvo':
                return this[ elem ];
            break;
            case 'max':
                return this[ 'max' + elem ];
            break;
            case 'name':
                switch(elem) {
                    case 'hair':  
                    return 'Волосы'; break;
                    case 'beard': 
                    return 'Борода'; break;
                    case 'cloth': 
                    return 'Вверх'; break;
                    case 'pants': 
                    return 'Низ'; break;
                    case 'fwear': 
                    return 'Обувь'; break;
                }
            break;
        }
    },

    ready() {
        $('#ready-hide').addClass('none');
        $('#confirm-hide').removeClass('none');
    },

    noready() {
        $('#ready-hide').removeClass('none');
        $('#confirm-hide').addClass('none');
    },

    saveSett() {
        if (this.pageAvai()) {
            $.ajax({
                url: 'core/ajax/savesett.php',
                method: 'POST',
                data: {
                    save_sett: true,
                    hair: this['hair'],
                    beard: this['beard'],
                    cloth: this['cloth'],
                    pants: this['pants'],
                    fwear: this['fwear'],
                    token: PageLoad.token
                },
                success: function(data) {
                    if (data) {
                        data = JSON.parse(data);
                        Costumize.messHandler(data);
                    }
                }
            });
        }
    },

    view(styleElem) {
        $('#style_elem_name').removeClass('none');
        $('#elem_name').html( this.elemData('name', styleElem) );
        $('#elem_colvo').html( this.elemData('colvo', styleElem) );
        $('#elem_max').html( this.elemData('max', styleElem) );

        $('#' + styleElem).removeClass();
        $('#' + styleElem).addClass(styleElem + this[styleElem]);

        let arr = ['hair', 'beard', 'cloth', 'pants', 'fwear'];

        for (let i = 0; i < arr.length; i++) {
            $('#' + arr[i] + '-colvo').html(
                this[arr[i]]
            );
            $('#' + arr[i] + '-max').html(
                this['max' + arr[i]]
            );
        }
    }
};

let Game = {
    construct() {
        this.workpath      = ['/game', '/item', '/craft', '/refuge'];
        this.actions       = ['srchloc', 'srchlut', 'eat', 'drink', 'sleep', 'nadet', 'craft', 'read', 'enterrefuge', 'uprefuge'];
        this.interval_time = 1000;
        this.sleep_time    = 1;
    },

    messHandler(data) {
        if (data['dom_data']) {
            this.view(data);
        } else if (data['popup'] && data['reload']) {
            Popup.activate(data['message']);
            PageLoad.loadPage(window.location.pathname + window.location.search);
        } else if (data['popup']) {
            Popup.activate(data['message']);
        } else if (data['page']) {
            PageLoad.loadPage(data['page']);
        } else if (data['reload']) {
           PageLoad.loadPage(window.location.pathname + window.location.search);
        }  
    },

    pageAvai() {
        for (let i = 0; i < this.workpath.length; i++) {
            if (this.workpath[i] === window.location.pathname) return true;
        }
    },

    actionAvai: function(action) {
        for (let i = 0; i < this.actions.length; i++) {
            if (this.actions[i] == action) return true;
        }
    },

    actionPrepare(action) {
        let data;
        switch(action) {
            case 'srchloc':
                data = {
                    from: window.location.pathname.split('/')[1],
                    token: PageLoad.token
                }; this.gameAction(action, data);
                break;
            case 'srchlut':
                data = {
                    from: window.location.pathname.split('/')[1],
                    token: PageLoad.token
                }; this.gameAction(action, data);
                break;
            case 'eat':
                let eat = new URLSearchParams(window.location.search);
                data = {
                    id_item: eat.get('id'),
                    from: window.location.pathname.split('/')[1],
                    token: PageLoad.token
                }; this.gameAction(action, data);
                break;
            case 'drink':
                let drink = new URLSearchParams(window.location.search);
                data = {
                    id_item: drink.get('id'),
                    from: window.location.pathname.split('/')[1],
                    token: PageLoad.token
                }; this.gameAction(action, data);
                break;
            case 'sleep':
                data = {
                    hours: game.data['sleep_time'],
                    from: window.location.pathname.split('/')[1],
                    token: PageLoad.token
                }; this.gameAction(action, data);
                break;
            case 'nadet':
                let nadet = new URLSearchParams(window.location.search);
                data = {
                    id_item: nadet.get('id'),
                    from: window.location.pathname.split('/')[1],
                    token: PageLoad.token
                }; this.gameAction(action, data);
                break;
            case 'craft':
                let crafting = new URLSearchParams(window.location.search);
                data = {
                    id: crafting.get('id'),
                    item: crafting.get('item'),
                    type: crafting.get('type'),
                    colvo: craft.data['colvo'],
                    from: window.location.pathname.split('/')[1],
                    token: PageLoad.token
                }; this.gameAction(action, data);
                break;
            case 'read':
                let read = new URLSearchParams(window.location.search);
                data = {
                    id_item: read.get('id'),
                    from: window.location.pathname.split('/')[1],
                    token: PageLoad.token
                }; this.gameAction(action, data);
            break;
            case 'enterrefuge':
                data = {
                    token: PageLoad.token
                }; this.refugeAction(action, data);
            break;
            case 'uprefuge':
                data = {
                    token: PageLoad.token
                }; this.refugeAction(action, data);
            break;
        }
    },

    gameAction(action, data) {
        if (this.pageAvai()) {
            $.ajax({
                url: 'core/ajax/gameactions.php?action=' + action,
                data: data,
                method: 'POST',
                success: function(data) {
                    if (data) {
                        data = JSON.parse(data);
                        Game.messHandler(data);
                    }
                }
            });
        }
    },

    refugeAction(action, data) {
        if (this.pageAvai()) {
            $.ajax({
                url: 'core/ajax/refugeactions.php?action=' + action,
                data: data,
                method: 'POST',
                success: function(data) {
                    if (data) {
                        data = JSON.parse(data);
                        Game.messHandler(data);
                    }
                }
            });
        }
    },

    view(move, data) {
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
                $('#sleep_time').html(this.sleep_time);
                break;
        }
    }
};

let Craft = {
    construct() {
        this.workpath = '/craft';
        this.colvo    = 1;
    },

    messHandler(data) {
        if (data['dom_data']) {
            this.view(data);
        } else if (data['popup'] && data['reload']) {
            Popup.activate(data['message']);
            PageLoad.loadPage(window.location.pathname + window.location.search);
        } else if (data['popup']) {
            Popup.activate(data['message']);
        } else if (data['page']) {
            PageLoad.loadPage(data['page']);
        } else if (data['reload']) {
           PageLoad.loadPage(window.location.pathname + window.location.search);
        }  
    },

    pageAvai() {
        if (this.workpath === window.location.pathname) return true;
    },

    view(move, data) {
        switch(move) {
            case 'colvo':
                $('#colvo_craft').html(this.colvo);
            break;
        }
    },
};

class Controller {
    constructor() {
        BarsMove.construct();

        PageLoad.construct();
        Auth.construct();
        Costumize.construct();
        Game.construct();
        Craft.construct();
    }

    allEvents() {
        this.pageloadEvents();
        this.costumizeEvents();
        this.gameEvents();

        $('body').on('click', function(e) {
            switch(e.target.id) {
                case 'close-btn':
                    Popup.close();
                    break;
                case 'menu-btn':
                    Menu.openclose('auto');
                break;
                case 'menu-target':
                    Menu.openclose('close');
                    break;
                case 'enter':
                    if ($('#enter-name').val() == '' || $('#enter-pass').val() == '') 
                        Popup.activate('Заполните поля!');
                    else Auth.enter();
                    break;
                case 'reg':
                    if ($('#reg-name').val() == '' || $('#reg-pass').val() == '' || $('#reg-mail').val() == '') 
                        Popup.activate('Заполните поля!');
                    else Auth.reg();
                    break;
                case 'ready':
                    Costumize.ready();
                    break;
                case 'costumize_no':
                    Costumize.noready();
                    break;
                case 'costumize_yes':
                    Costumize.saveSett();
                    break;
                case 'sleep_no':
                    Game.view('sleep_no');
                    break;
                case 'sleep_yes':
                    Game.actionPrepare('sleep');
                    break;
                case 'sleep':
                    Game.view('sleep_range');
                    break;
            }
        });

        $('body').on('change', function(e) {
            switch(e.target.id) {
                case 'sleep_range_input':
                    Game.sleep_time = $('#' + e.target.id).val();
                    Game.view('sleep_time');
                break;
                case 'colvo_range_input':
                    Craft.colvo = $('#' + e.target.id).val();
                    Craft.view('colvo');
                break;
            }
        });
    }

    pageloadEvents() {
        $('body').on('click', 'a.ajax', function(e) {
            e.preventDefault();
            PageLoad.loadPage($(this).attr('href'));
        });
        window.onpopstate = function(e) {
            let page = (e.state && e.state.page) || 'game';
            PageLoad.loadPage(page);
        }
        if (performance.navigation.type == 1) {
            if (PageLoad.routeAvai(window.location.pathname)) {
                PageLoad.loadPage(window.location.pathname + window.location.search);
            }
        } else {
            PageLoad.loadPage('/auth');
        }
    }

    costumizeEvents() {
        $('body').on('click', 'button.cost-prevnext-btn', function(e) {
            let move      = e.target.id.split('-')[0];
            let styleElem = e.target.id.split('-')[1];
            Costumize.costumize(move, styleElem);
        });
    }

    gameEvents() {
        $('body').on('click', 'button.move-btn', function(e) {
            if (Game.actionAvai(e.target.id)) BarsMove.activate(e.target.id);
        });
    }
}

let controller = new Controller();
    controller.allEvents();