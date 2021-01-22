import {GameLive} from '../js/game/GameLive.js';
import {Camera} from '../js/game/Camera.js';
import {GameWorld} from '../js/game/GameWorld.js';
import {Player} from '../js/game/Player.js';

(function() {
    'use strict';

    let Resources = {
        loadSprites: function(all_sprites)
        {
            this.load        = 0;
            this.all_sprites = all_sprites;
            let sprites      = {};

            for(let i = 0; i < all_sprites.length; i++)
            {
                let sprite    = new Image();
                sprite.src    = all_sprites[i].path + all_sprites[i].nm + '.png';
                sprite.onload = () => {this.load+=1};

                sprites[ all_sprites[i]['nm'] ] = sprite;
            }

            this.check = setInterval(function() { Resources.checkLoad() }, 1);

            return sprites;
        },
        checkLoad: function()
        {
            if (this.load >= this.all_sprites.length)
            {
                Game.create();
                clearInterval(this.check);
            }
        }
    };

    let Updater = {
        update: function() 
        {
            if (window.location.pathname !== '/game') clearInterval(this.timer);

            let params = new FormData();
        	params.append('x', Game.player.x);
        	params.append('y', Game.player.y);
        	params.append('s', Game.palyer.s);
        	params.append('time', Game.gameLive.time);
        	params.append('weather', Game.gameLive.weather);
        	params.append('weatherTime', Game.gameLive.weatherTime);
        	params.append('temp', Game.gameLive.temp);
        	params.append('loc', Game.world.loc);
        	params.append('loc_explored', Game.world.loc_explored);
        	params.append('hp', Game.player.hp);
        	params.append('hpTime', Game.player.hpTime);
        	params.append('hung', Game.player.hung);
        	params.append('hungTime', Game.player.hungTime);
        	params.append('thirst', Game.player.thirst);
        	params.append('thirstTime', Game.player.thirstTime);
        	params.append('fatigue', Game.player.fatigue);
        	params.append('fatigueTime', Game.player.fatigueTime);
        	params.append('token', localStorage.getItem('token'));

            axios.post('/core/ajax/gameload.php?action=update', params)
    		.then((response) => {})
    		.catch((error) => {
    			console.log(error)
    		})
        },
        pagedate: function(dt) 
        {
            let info_elems = ['hp', 'hung', 'thirst', 'fatigue'];

            for(let i = 0; i < info_elems.length; i++)
            {
                this.elems[ info_elems[ i ] ].html(Game.player[ info_elems[ i ] ]);
            }

            this.elems.time.html( Utils.convertTime(Math.floor(Game.gameLive.time)) );
            this.elems.temp.html( Game.temps[ Game.gameLive.temp ]['nm'] );
            this.elems.weather_name.html( Game.weathers[ Game.gameLive.weather ]['nm'] );
            this.elems.weather_img.attr('src', Game.weathers[ Game.gameLive.weather ]['img'] );
            this.elems.fps.html(Math.floor((1000 / dt) / 1000));
        },
        start: function()
        {
            this.elems = {
                hp: document.getElementById('hp'),
                hung: document.getElementById('hung'),
                thirst: document.getElementById('thirst'),
                fatigue: document.getElementById('fatigue'),
                time: document.getElementById('time'),
                temp: document.getElementById('temp'),
                weather_name: document.getElementById('weather_name'),
                weather_img: document.getElementById('weather_img'),
                fps: document.getElementById('fps')
            };

            this.timer = setInterval(function() {
                Updater.update();
            }, 5000);
        }
    };

    let Utils = {
        rand: function(min, max) 
        {
            min = Math.ceil(min);
            max = Math.floor(max);
            return Math.floor(Math.random() * (max - min + 1)) + min;
        },
        convertTime: function( time ) 
        {
            let minutes = Math.floor(time / 60);
            let hours   = Math.floor(minutes / 60);
            minutes     = minutes - (hours * 60);

            if (hours < 10) {
                if (minutes < 10) 
                    return '0' + hours + ':0' + minutes;
                else 
                    return '0' + hours + ':' + minutes;
            } else {
                if (minutes < 10) 
                    return hours + ':0' + minutes;
                else 
                    return hours + ':' + minutes;
            }
        }
    };

    let Game = {
        loop: function() 
        {
            // Sys
            let dt = (Date.now() - Game.lastDt) / 1000;

            Game.update( dt );
            Game.render( dt );

            Game.lastDt = Date.now();

            if (window.location.pathname == '/game') window.requestAnimationFrame(Game.loop);
        },

        update: function(dt)
        {
            this.gameLive.update(dt, this.canv);
            this.player.update(dt, this.loc.width, this.loc.height);
            this.camera.update(dt, this.player.x, this.player.y, this.loc.width, this.loc.height);
            
            Updater.pagedate(dt);
        },

        render: function()
        {
            // Game
            this.ctx.save();
            this.ctx.translate(-this.camera.x, -this.camera.y);

                    this.world.render(this.ctx, this.camera);
                    this.player.render(this.ctx);

            this.ctx.restore();

            this.gameLive.render(this.ctx, this.canv);
        },

        start: function()
        {
            Game.loop();
            document.getElementById('game_canv_loader').style.display = 'none'; // Загрузка...
        },

        create: function()
        {
            // Sys
            this.canv                      = document.getElementById('game_canv');
            this.canv.width                = 400;
            this.canv.height               = 400;

            this.ctx                       = this.canv.getContext('2d', {alpha: false});
            this.ctx.imageSmoothingEnabled = false;
            this.ctx.font                  = '13px Montserrat';

            this.lastDt = Date.now();
            this.pause  = false;

            // Game
            this.gameLive = new GameLive(this.data.game.time, this.data.game.weather, this.data.game.temp, this.data.game.weatherTime);
            this.camera   = new Camera(0, 0, this.canv.width, this.canv.height);
            this.world    = new GameWorld(this.sprites, this.data.game.loc, this.data.game.loc_explored);
            this.player   = new Player(
                this.data.game.x,
                this.data.game.y,
                this.data.game.s,
                this.data.game.hp,
                this.data.game.hung,
                this.data.game.thirst,
                this.data.game.fatigue,
                this.data.game.hpTime,
                this.data.game.hungTime,
                this.data.game.thirstTime,
                this.data.game.fatigueTime,
                this.sprites['pl']
            );

            this.weathers = this.data.sys.weathers;
            this.temps    = this.data.sys.temps;

            this.loc      = {width: 1024, height: 1024};

            this.start();
        },

        load: function()
        {
        	let params = new FormData();
        	params.append('token', localStorage.getItem('token'));

    		axios.post('/core/ajax/gameload.php?action=load', params)
    		.then((response) => {
    			Game.data       = JSON.parse(data);
                let all_sprites = [
                    {nm: 'pl', path: '/assets/game/'}, 
                    {nm: 'loc_' + Game.data.game.loc, path: '/assets/game/'}
                ];
                Game.sprites    = Resources.loadSprites(all_sprites);
    		})
    		.catch((error) => {
    			console.log(error)
    		})
        }
    }; // end of game

    Updater.start();
    Game.load();
})(); // 6 stroke