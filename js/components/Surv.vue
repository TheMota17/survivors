<template>
	<script type='module'>
		import {Utils} from '../js/game/Utils.js';
		import {Resources} from '../js/game/Resources.js';
		import {Updater} from '../js/game/Updater.js';

		import {GameLive} from '../js/game/GameLive.js';
		import {Camera} from '../js/game/Camera.js';
		import {GameWorld} from '../js/game/GameWorld.js';
		import {Player} from '../js/game/Player.js';
		import {Bullet} from '../js/game/Bullet.js'; 
		import {BulletEmitter} from '../js/game/BulletEmitter.js';

		(function()
		{
		    'use strict';

		    let Game = {
		        loop() 
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
		            this.bulletEmitter.update(dt);
		            this.camera.update(dt, this.player.x, this.player.y, this.loc.width, this.loc.height);
		            
		            Updater.pagedate(dt, this, Utils);
		        },

		        render()
		        {
		            // Game
		            this.ctx.save();
		            this.ctx.translate(-this.camera.x, -this.camera.y);

		                    this.world.render(this.ctx, this.camera);
		                    this.player.render(this.ctx);
		                    this.bulletEmitter.render(this.ctx);

		            this.ctx.restore();

		            this.gameLive.render(this.ctx, this.canv);
		        },

		        start()
		        {
		            Game.loop();
		            document.getElementById('game_canv_loader').style.display = 'none'; // Загрузка...
		        },

		        create()
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
		            let bullets = [];
		            for(let i = 0; i <= 100; i++) 
		            {
		            	bullets.push(new Bullet());
		            }
		            this.bulletEmitter = new BulletEmitter(bullets, this.player, this.canv, this.camera);

		            this.weathers = this.data.sys.weathers;
		            this.temps    = this.data.sys.temps;
		            this.locs     = this.data.sys.locs;

		            this.loc      = {width: 1024, height: 1024};

		            /* this.timer    = setInterval(() => {
			            Updater.update(Game);
			        }, 5000); */

		            this.start();
		        },

		        load()
		        {
		        	let params = new FormData();
		        	params.append('token', localStorage.getItem('token'));

		    		axios.post('/core/ajax/Game_load.php?action=load', params)
		    		.then((response) => {
		    			Game.data    = response.data;

		                let sprites  = [
		                    {nm: 'pl', path: '/assets/game/'}, 
		                    {nm: 'loc_' + Game.data.game.loc, path: '/assets/game/'}
		                ];
		                Game.sprites = Resources.loadSprites(sprites);

		                let check = setInterval(() => {
		                	if (Resources.checkLoad()) { 
		                		Game.create();
		                		clearInterval(check);
		                	}
		                }, 1);
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
</template>