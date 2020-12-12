let popup = {
    methods: {
        events: function() {
            $('#close-btn').on('click', function() {
                popup.methods.close();
            });
        },
        activate: function(text) {
            $('#main-txt').html(text);
            $('#pop-up').removeClass('none');
        },
        close: function() {
            $('#main-txt').empty();
            $('#pop-up').addClass('none');
        }
    },
    start: function() {
        popup.methods.events();
    }
};

let menu = {
    data: {
        'workpath': ['/game', '/ivent', '/item', '/craft', '/refuge']
    },
    methods: {
        events: function() {
            $('#menu-btn').on('click', function() {
                menu.methods.openclose('auto');
            });
            $('#menu-target').on('click', function() {
                menu.methods.openclose('close');
            });
        },
        pageAvai: function(page) {
            for(let i = 0; i < menu.data['workpath'].length; i++) {
                if (page == menu.data['workpath'][i]) return true;
            }
        },
        hide: function(page) {
            if (! menu.methods.pageAvai(page)) {
                if (document.getElementById('menu')) {
                    if (document.getElementById('menu').classList.contains('none')) $('#menu').removeClass('none');
                }
            }
        },
        openclose: function(move) {
            switch (move) {
                case 'open':
                    $('#menu').addClass('menu-active');
                    $('#menu-target').removeClass('none');
                    $('#menu-target').addClass('menu-target-active');
                    break;
                case 'close':
                    $('#menu').removeClass('menu-active');
                    $('#menu-target').addClass('none');
                    $('#menu-target').removeClass('menu-target-active');
                    break;
                case 'auto':
                    $('#menu').toggleClass('menu-active');
                    $('#menu-target').toggleClass('none');
                    $('#menu-target').toggleClass('menu-target-active');
                    break;
            }
        }
    },
    start: function() {
        menu.methods.events();
    }
};

let barsMove = {
    data: {
        'action': 0,
        'btntext': 0,
        'iteration': 0,
        'interval': 0
    },
    methods: {
        activate: function(action) {
            if (barsMove.data['action'] == 0) {

                barsMove.data['action']   = action;
                barsMove.data['btntext']  = $('#txt_' + action).html();
                barsMove.data['interval'] = setInterval(barsMove.model, 500);
                barsMove.view('textchange');
                
            } else if (barsMove.data['action'] == action) {
                barsMove.methods.clear();
            }
        },
        clear: function() {
            clearInterval(barsMove.data['interval']);

            barsMove.view('textreturn');
            barsMove.view('barreturn');

            for (data in barsMove['data']) {
                barsMove.data[data] = 0;
            }
        },
        barMove: function() {
            $('#bar_' + barsMove.data['action']).css('width', barsMove.data['iteration'] + '%');
        },
        actionSend: function(action) {
            game.methods.actionSend(action);
        }
    },
    model: function() {
        if (barsMove.data['iteration'] < 100) {
            barsMove.data['iteration'] += 20;
            barsMove.methods.barMove();
        } else {
            barsMove.methods.actionSend(barsMove.data['action']);
            barsMove.methods.clear();
        }
    },
    view: function(move) {
        switch (move) {
            case 'textchange':
                $('#txt_' + barsMove.data['action']).html('Отмена');
                break;
            case 'textreturn':
                $('#txt_' + barsMove.data['action']).html(barsMove.data['btntext']);
                break;
            case 'barreturn':
                $('#bar_' + barsMove.data['action']).css('width', 0 + '%');
                break;
        }
    },
    start: function() {
        //
    }
};