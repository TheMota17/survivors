<template>
	<script type='module'>
		import {Utils} from '../js/game/Utils.js';
		import {Resources} from '../js/game/Resources.js';
		import {Updater} from '../js/game/Updater.js';

		import {GameLive} from '../js/game/GameLive.js';
		import {Camera} from '../js/game/Camera.js';
		import {GameWorld} from '../js/game/GameWorld.js';
		import {Player} from '../js/game/Player.js';
		import {Enemy} from '../js/game/Enemy.js';
		import {EnemyEmitter} from '../js/game/EnemyEmitter.js';
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
		            Game.render();

		            Game.lastDt = Date.now();

		            if (window.location.pathname == '/') window.requestAnimationFrame(Game.loop);
		        },

		        update: function(dt)
		        {
		            this.gameLive.update(dt);
		            this.enemyEmitter.update(dt);
		            this.player.update(dt);
		            this.bulletEmitter.update(dt);
		            this.camera.update(dt);
		            
		            Updater.pagedate(dt, this, Utils);
		        },

		        render()
		        {
		            // Game
		            this.ctx.save();
		            this.ctx.translate(-this.camera.x, -this.camera.y);

		                    this.world.render();
		                    this.enemyEmitter.render();
		                    this.player.render();
		                    this.bulletEmitter.render();

		            this.ctx.restore();

		            this.gameLive.render();
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
		            this.lastDt                    = Date.now();
		            this.pause                     = false;

		            // Game
		            this.loc = {width: 1024, height: 1024};

		            this.gameLive = new GameLive(this.ctx, this.canv, this.data);
		            this.player   = new Player(this, this.ctx, this.data, this.loc, this.sprites['pl']);
		            this.camera   = new Camera(this.canv, 0, 0, this.loc, this.player);
		            this.world    = new GameWorld(this.ctx, this.sprites, this.data);

		            this.bullets = [];
		            for(let i = 0; i <= 100; i++) 
		            { this.bullets.push(new Bullet()); }

		            this.enemys = [];
		            for(let i = 0; i < 1; i++)
		            {
		            	this.enemys.push(new Enemy({width: 1024, height: 1024}, {x: 100, y: 100, dx: 0, dy: 0, nm: 'HasM', s: 60, hp: 100, die: false, img: this.sprites['pl']}));
		            }
		            this.bulletEmitter = new BulletEmitter(this.ctx, this.canv, this.player, this.camera, this.enemys, this.bullets);
		            this.enemyEmitter  = new EnemyEmitter(this.ctx, this.enemys, this.bulletEmitter);

		            this.weathers = this.data.sys.weathers;
		            this.temps    = this.data.sys.temps;
		            this.locs     = this.data.sys.locs;

		            this.timer    = setInterval(() => Updater.update(Game), 5000);

		            this.start();
		        },

		        load()
		        {
		        	let params = new FormData();
		        	params.append('token', localStorage.getItem('token'));

		    		axios.post('/core/GameLoad/?action=load', params)
		    		.then((response) => 
		    		{
		    			Game.data    = response.data;

		                let sprites  = 
		                [
		                    {nm: 'pl', path: '/assets/game/'}, 
		                    {nm: 'loc_' + Game.data.game.loc, path: '/assets/game/'}
		                ];
		                Game.sprites = Resources.loadSprites(sprites);

		                let check = setInterval(() => 
		                {
		                	if (Resources.checkLoad()) 
		                	{ 
		                		Game.create();
		                		clearInterval(check);
		                	}
		                }, 1);
		    		})
		        }
		    }; // end of game

		    Updater.start();
		    Game.load();
		})();
	</script>
</template>