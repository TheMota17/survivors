<template>
	<script type='module'>
		import {Utils} from '../js/game/Utils.js';
		import {Resources} from '../js/game/Resources.js';

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
		            Game.render( dt );

		            Game.lastDt = Date.now();

		            if (window.location.pathname == '/') window.requestAnimationFrame(Game.loop);
		        },
		        update: function(dt)
		        {
		            this.GameLive.update(dt);

		            for(let i = 0; i < this.Players.length; i++)
		            { this.Players[i].update(dt) }

		            this.Camera.update(dt);
		        },
		        render(dt)
		        {
		            // Game
		            this.ctx.save();
		            this.ctx.translate(-this.Camera.x, -this.Camera.y);

		                    this.World.render();

		                    for(let i = 0; i < this.Players.length; i++)
				            { this.Players[i].render(dt); }

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
		            this.Players  = [];

		            for (let i = 0; i < ajaxData.players.length; i++)
		            {
		            	this.Players.push(new Player(this, this.sprites['pl'], ajaxData.players[i]));
		            }

		            this.Camera   = new Camera(this);
		            this.World    = new GameWorld(this, this.sprites);

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
		    			Game.Player = Game.getUserFromPlayers(response.data.players);

		                let sprites = [
		                    {nm: 'pl', path: '/assets/game/'},
		                    {nm: 'loc_' + Game.Player.loc, path: '/assets/game/'}
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

		        getCtx() { return this.ctx },

		        getPlayer() { return this.Player },

		        getPlayers() { return this.Players },

		        getGameLive() { return this.GameLive },

		        getCamera() { return this.Camera },

		        getWorld() { return this.World },

		        getAjaxData() { return this.ajaxData },

		        getLoc() { return this.loc },

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