<template>
	<div>
		<div class='flex j-c mt10'>
		    <div class='game-meteoсharact backgr2 flex j-sa pt5 pb5'>
		        <div class='flex j-c ai-c'>
		            <img class='item14-1 mr5' src='/assets/icons/time.png'><span id='time'>0</span>
		        </div>
		        <div class='flex j-c ai-c'>
		            <img class='item14-1 mr5' src='/assets/icons/weather-1.png' id='weather_img'>
		            <span id='weather_name'>0</span>
		        </div>
		        <div class='flex j-c ai-c'>
		            <img class='item14-1 mr5' src='/assets/icons/temp.png'><span id='temp'>0</span>°
		        </div>
		    </div>
		</div>

		<div class='flex j-c mt10'>
		    <div class='game-location backgr2 pb5'>
		        <div class='flex j-c ai-c pt5 pb5 fnt15'>
		            <img src='/assets/icons/loc.png' class='item14-1 mr5' />
		            <div class='loc-name mr5' id='loc_name'>0</div>
		        </div>
	            <div class='flex j-c pb5 fnt12'>
	                <span class='mr5'>Исследовано </span><span id='loc_explored'>0</span>%
	            </div>

		        <div class='wdth100 relative flex j-c'>
		            <div class='game-canvas-loader flex j-c ai-c' id='game_canv_loader'>
		                <div class='flex j-c ai-c'>
		                    Загрузка...
		                </div>
		            </div>
		            <div class='game-canvas-fps'>
		                <div class='flex j-s ai-c ml10'>
		                    <div class='flex'>
		                        FPS: <span class='ml5' id='fps'>0</span>
		                    </div>
		                </div>
		            </div>
		            <div class='game-canvas-btns flex j-e ai-e'>
		                <div class='game-canvas-btns-wdth flex j-c ai-c fl-di-co'>
		                    <div class='flex j-c ai-c'>
		                        <button class='game-btn game-btn-min mb5 mr5' id='up_l'></button>
		                            <button class='game-btn game-btn-big' id='up'></button>
		                        <button class='game-btn game-btn-min mb5 ml5' id='up_r'></button>
		                    </div>
		                    <div class='flex j-c'>
		                        <button class='game-btn game-btn-big mr10' id='left'></button>
		                        <button class='game-btn game-btn-big ml10' id='right'></button>
		                    </div>
		                    <div class='flex j-c'>
		                        <button class='game-btn game-btn-min mt5 mr5' id='down_l'></button>
		                            <button class='game-btn game-btn-big' id='down'></button>
		                        <button class='game-btn game-btn-min mt5 ml5' id='down_r'></button>
		                    </div>
		                </div>
		            </div>
		            <canvas class='game-canvas flex j-c ai-c' id='game_canv' width='400' height='400'>Данная технология не поддерживается вашим браузером</canvas>
		        </div>

	            <div class='flex j-c mt5'>
	                <div class='loc-whatsrch'>
	                    <div class='mb5 '>Можно найти:</div>
	                    <div class='game-avai-items flex' id='available_items'>
	                       
	                    </div>
	                </div>
	            </div>

		    </div>
		</div>

		<div class='flex j-c mt10'>
		    <Sleep></Sleep>
		</div>

		<script type='module'>
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
		        	params.append('s', Game.player.s);
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
		                this.elems[ info_elems[ i ] ].innerHTML = Game.player[ info_elems[ i ] ];
		            }

		            this.elems.time.innerHTML = Utils.convertTime(Math.floor(Game.gameLive.time));
		            this.elems.temp.innerHTML = Game.temps[ Game.gameLive.temp ]['nm'];
		            this.elems.weather_name.innerHTML = Game.weathers[ Game.gameLive.weather ]['nm'];
		            this.elems.weather_img.src = Game.weathers[ Game.gameLive.weather ]['img'];
		            this.elems.fps.innerHTML = Math.floor((1000 / dt) / 1000);
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
		            /*
		            this.timer = setInterval(function() {
		                Updater.update();
		            }, 5000);
		            */
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

		            if (window.location.pathname == '/') window.requestAnimationFrame(Game.loop);
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
		    			Game.data       = response.data;
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
		})();
		</script>
	</div>
</template>

<script>
let Sleep = httpVueLoader('../components/Sleep.vue')

module.exports = {
	name: 'Game',
	components: {
		Sleep
	},
	beforeMount() {
		let params = new FormData();
    	params.append('token', localStorage.getItem('token'));

		axios.post('/core/ajax/api.php?page=game', params)
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
</script>