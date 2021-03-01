<template>
	<script type='module'>
		import {Utils} from '../js/game/Utils.js';
		import {Resources} from '../js/game/Resources.js';
		import {Updater} from '../js/game/Updater.js';

		import {GameLive} from '../js/game/GameLive.js';
		import {Camera} from '../js/game/Camera.js';
		import {GameWorld} from '../js/game/GameWorld.js';
		import {Player} from '../js/game/Player.js';

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
		            this.GameLive.update(dt);
		            this.Player.update(dt);
		            this.Camera.update(dt);
		            
		            Updater.pagedate(dt, this, Utils);
		        },
		        render()
		        {
		            // Game
		            this.ctx.save();
		            this.ctx.translate(-this.Camera.x, -this.Camera.y);

		                    this.World.render();
		                    this.Player.render();

		            this.ctx.restore();

		            this.GameLive.render();
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

		            this.GameLive = new GameLive(this);
		            this.Player   = new Player(this, this.sprites['pl']);
		            this.Camera   = new Camera(this);
		            this.World    = new GameWorld(this, this.sprites);

		            this.weathers = this.AjaxData.sys.weathers;
		            this.temps    = this.AjaxData.sys.temps;
		            this.locs     = this.AjaxData.sys.locs;

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
		                let sprites = 
		                [
		                    {nm: 'pl', path: '/assets/game/'},
		                    {nm: 'loc_' + response.data.game.loc, path: '/assets/game/'}
		                ];
		                Game.AjaxData = response.data;
		                Game.sprites  = Resources.loadSprites(sprites);

		                let check = setInterval(() => 
		                {
		                	if (Resources.checkLoad()) 
		                	{ 
		                		Game.create();
		                		clearInterval(check);
		                	}
		                }, 1);
		    		})
		        },
		        getCanv() { return this.canv },
		        getCtx() { return this.ctx },
		        getPlayer() { return this.Player },
		        getGameLive() { return this.GameLive },
		        getCamera() { return this.Camera },
		        getWorld() { return this.World },
		        getAjaxData() { return this.AjaxData },
		        getLoc() { return this.loc },
		        getBulletEmitter() { return this.BulletEmitter },
		        getEnemyEmitter() { return this.EnemyEmitter }

		    }; // end of game

		    Updater.start();
		    Game.load();
		})();
	</script>
</template>