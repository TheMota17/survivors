<template>
	<script type='module'>
		import {Utils} from '../js/game/Utils.js';
		import {Resources} from '../js/game/Resources.js';


		import {GameLive} from '../js/game/GameLive.js';
		import {Camera} from '../js/game/Camera.js';
		import {GameWorld} from '../js/game/GameWorld.js';
		import {Player} from '../js/game/Player.js';
		import {Enemy} from '../js/game/Enemy.js';

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

		            window.requestAnimationFrame(Game.loop);
		        },
		        update: function(dt)
		        {
		        	if (window.location.pathname == '/')
		        	{
		        		this.GameLive.update(dt);
			            this.Player.update(dt);

			            for(let i = 0; i < this.Enemys.length; i++)
			            { this.Enemys[i].update(dt) }

			            this.Camera.update(dt);

			            if (Utils.byId('time'))
						{
							Utils.byId('time').innerHTML = Utils.convertTime( this.Player.time );
							Utils.byId('weather_name').innerHTML = this.weathers[ this.Player.weather ]['nm'];
							Utils.byId('weather_img').src = this.weathers[ this.Player.weather ]['img'];
							Utils.byId('temp').innerHTML = this.temps[ this.Player.temp ]['nm'];
							Utils.byId('loc_name').innerHTML = this.locs[ this.Player.loc ]['nm'];
							Utils.byId('loc_explored').innerHTML = this.Player.loc_explored;
							Utils.byId('fps').innerHTML = Math.floor((1000 / dt) / 1000);
						}
		        	}
		        },
		        render(dt)
		        {
		            // Game
		            this.ctx.save();
		            this.ctx.translate(-this.Camera.x, -this.Camera.y);

		                    this.World.render();
		                    this.Player.render();
		                    for(let i = 0; i < this.Enemys.length; i++)
				            { this.Enemys[i].render(); }

		            this.ctx.restore();

		            this.GameLive.render();
		        },
		        start()
		        {
		            Game.loop();
		            document.getElementById('game_canv_loader').style.display = 'none'; // Загрузка...
		        },
		        create(ajaxData)
		        {
		            // Sys
		            this.canv                      = document.getElementById('game_canv');
		            this.gameBtns                  = document.getElementById('game_btns');
		            this.canv.width                = 400;
		            this.canv.height               = 400;

		            this.ctx                       = this.canv.getContext('2d', {alpha: false});
		            this.ctx.imageSmoothingEnabled = false;
		            this.ctx.font                  = '13px Montserrat';
		            this.lastDt                    = Date.now();
		            this.pause                     = false;

		            // Game
		            this.GameLive = new GameLive(this);

		            this.Enemys   = [];
		            this.Player   = new Player(this, this.getUserFromPlayers(ajaxData.players));

		            for (let i = 0; i < ajaxData.players.length; i++)
		            {
		            	if (!ajaxData.players[i].you)
		            	{
		            		this.Enemys.push(new Enemy(this, ajaxData.players[i]));
		            	}
		            }

		            this.Camera = new Camera(this);
		            this.World  = new GameWorld(this, this.sprites, ajaxData.sys.locs[ this.Player['loc'] ]['loc_width'], ajaxData.sys.locs[ this.Player['loc'] ]['loc_height']);

		            this.weathers = ajaxData.sys.weathers;
		            this.temps    = ajaxData.sys.temps;
		            this.locs     = ajaxData.sys.locs;

		            this.start();
		        },
		        load()
		        {
		        	let params = new FormData();
		        	params.append('token', localStorage.getItem('token'));

		    		axios.post('/core/Game/?action=getData', params)
		    		.then((response) =>
		    		{
		                let sprites = [
		                    {nm: 'loc_' + Game.getUserFromPlayers(response.data.players).loc, path: '/assets/game/'}
		                ];

		                Game.sprites  = Resources.loadSprites(sprites);

		                let check = setInterval(() =>
		                {
		                	if (Resources.checkLoad())
		                	{
		                		Game.create(response.data);
		                		clearInterval(check);
		                	}
		                }, 1);
		    		})
		        },

		        getCanv() { return this.canv },

		        getGameBtns() { return this.gameBtns },

		        getCtx() { return this.ctx },

		        getPlayer() { return this.Player },

		        getPlayers() { return this.Players },

		        getGameLive() { return this.GameLive },

		        getCamera() { return this.Camera },

		        getWorld() { return this.World },

		        getAjaxData() { return this.ajaxData },

		        getUserFromPlayers(Players)
		        {
		        	for(let i = 0; i < Players.length; i++)
		        	{
		        		if (Players[i].you) return Players[i];
		        	}
		        }
		    }; // end of game

		    Game.load();

		})();
	</script>
</template>

<script>
module.exports = {
	name: 'Surv'
}
</script>