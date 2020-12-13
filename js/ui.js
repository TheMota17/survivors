
let Popup = {
    activate(text) {
        $('#main-txt').html(text);
        $('#pop-up').removeClass('none');
    },
    close() {
        $('#main-txt').empty();
        $('#pop-up').addClass('none');
    }
};

let Menu = {
    hide: function(page) {
        if (! this.pageAvai(page)) {
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
};

let BarsMove = {
    construct() {
        this.action    = 0;
        this.btntext   = 0;
        this.iteration = 0;
        this.interval  = 0;
    },

    activate(action) {
        if (BarsMove.action == 0) {

            BarsMove.action   = action;
            BarsMove.btntext  = $('#txt_' + action).html();
            BarsMove.interval = setInterval(this.iter, 500);
            BarsMove.view('textchange');
                
        } else if (BarsMove.action == action) {
            BarsMove.clear();
        }
    },

    clear() {
        clearInterval(this.interval);

        this.view('textreturn');
        this.view('barreturn');

        this.action    = 0;
        this.btntext   = 0;
        this.iteration = 0;
        this.interval  = 0;
    },

    barMove() {
        $('#bar_' + this.action).css('width', this.iteration + '%');
    },

    iter() {
        if (BarsMove.iteration < 100) {
            BarsMove.iteration += 20;
            BarsMove.barMove();
        } else {
            Game.actionPrepare(BarsMove.action);
            BarsMove.clear();
        }
    },

    view(move) {
        switch (move) {
            case 'textchange':
                $('#txt_' + this.action).html('Отмена');
                break;
            case 'textreturn':
                $('#txt_' + this.action).html(this.btntext);
                break;
            case 'barreturn':
                $('#bar_' + this.action).css('width', 0 + '%');
                break;
        }
    }
};