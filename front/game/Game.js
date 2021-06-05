class Game {
    constructor()
    {
        this.resources = false;
        this.firstTouch = false;

        this.wsData = false;
        
        this.locImg = false;
        this.players = {};
        this.items = {};

        this.notifs = {
            exitPlayer: false,
            delItem: false
        };

        this.Pixi = new PIXI.Application({
            backgroundColor: 0x212121,
            antialias: true,
            resolution: 1
        }); document.getElementById('canvasWrapper').appendChild(this.Pixi.view);

        this.windowResize();
        this.Pixi.view.name = 'canvas';

        this.Loader = new PIXI.Loader();

        window.addEventListener('click', () => this.firstTouch = true);
        window.addEventListener('resize', () => this.windowResized());
        this.Pixi.view.addEventListener('click', (e) => this.input(e));

        this.Loader
            .add('player', '/front/assets/game/player.png')
            .add('healthBar', '/front/assets/game/hp.png')
            .add('loc_1', '/front/assets/game/loc_1.png')
            .add('11', '/front/assets/items/others/meat.png')
            .add('12', '/front/assets/items/others/water.png')
            .add('13', '/front/assets/items/others/wood.png')
            .add('14', '/front/assets/items/others/stick.png')
            .add('15', '/front/assets/items/others/rock.png')
            .add('16', '/front/assets/items/others/grin_rock.png')
            .add('17', '/front/assets/items/others/rope.png')
            .add('18', '/front/assets/items/others/dur_rope.png')
            .add('19', '/front/assets/items/others/arrow.png')
            .add('110', '/front/assets/items/others/craft_book2.png')
            .add('111', '/front/assets/items/others/craft_book3.png')
            .add('112', '/front/assets/items/others/craft_book4.png')
            .add('113', '/front/assets/items/others/bread.png')
            .add('114', '/front/assets/items/others/canned.png')
            .add('115', '/front/assets/items/others/soda.png')
            .add('116', '/front/assets/items/others/treated_wood.png')
            .add('117', '/front/assets/items/others/scrap.png')
            .add('118', '/front/assets/items/others/metall.png')

            .add('back', '/front/assets/sounds/back.mp3');

        this.Loader.load((loader, resources) => {
            this.resources = resources;
            
            this.ws = io(this.consts.WS_PORT, {transports: ['websocket']});
                this.ws.on('connect_error', () => console.log('Failed connection...'));
                this.ws.on('update', (response) => this.wsData = response.data);

            this.Pixi.ticker.add(delta => this.update(delta));
        });
    }

    update(delta)
    {
        if (!this.wsData) return;


    }

    windowResize()
    {
        this.Pixi.view.width = (window.innerWidth > this.consts.MAX_CANV_WDTH) ? this.consts.MAX_CANV_WDTH : window.innerWidth;
        this.Pixi.view.height = (window.innerHeight > this.consts.MAX_CANV_HGHT) ? this.consts.MAX_CANV_HGHT : window.innerHeight - 25;
    }
}