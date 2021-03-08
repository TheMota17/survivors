class GameLive {
	constructor(game)
	{
		this.game = game;

		this.rain = [];
		this.snow = [];
		for(let i = 0; i < 100; i++)
		{
			let x = Math.floor(Math.random() * (600 - 0 + 1)) + 0;
			let y = Math.floor(Math.random() * (-5 - -200 + 1)) + -200;
			this.rain.push({x: x, y: y, dx: -1, dy: 2});
			this.snow.push({x: x, y: y, dx: -1, dy: 2});
		}
	}

	update(dt)
	{
		switch(this.game.getPlayer().weather)
		{
			case 2:
			case 3:
				for(let i = 0; i < this.rain.length; i++)
				{
					if (this.rain[ i ].x < 0) {
						this.rain[ i ].x = Math.floor(Math.random() * (600 - 0 + 1)) + 0;
						this.rain[ i ].y = Math.floor(Math.random() * (-5 - -200 + 1)) + -200;
					} else {
						this.rain[ i ].x += this.rain[ i ].dx * (200 * dt);
						this.rain[ i ].y += this.rain[ i ].dy * (200 * dt);
					}
				}
			break;
			case 5:
				for(let i = 0; i < this.snow.length; i++)
				{
					if (this.snow[ i ].x < 0) {
						this.snow[ i ].x = Math.floor(Math.random() * (600 - 0 + 1)) + 0;
						this.snow[ i ].y = Math.floor(Math.random() * (-5 - -200 + 1)) + -200;
					} else {
						this.snow[ i ].x += this.snow[ i ].dx * (100 * dt);
						this.snow[ i ].y += this.snow[ i ].dy * (100 * dt);
					}
				}
			break;
		}
	}

	render()
	{
		switch(this.game.getPlayer().weather)
		{
			case 2:
			case 3:
				for(let i = 0; i < this.rain.length; i++)
				{
					this.game.getCtx().fillStyle = 'white';
					this.game.getCtx().fillRect(this.rain[i].x, this.rain[i].y, 1, 1);
				}
			break;
			case 5:
				for(let i = 0; i < this.snow.length; i++)
				{
					this.game.getCtx().fillStyle = 'white';
					this.game.getCtx().fillRect(this.snow[i].x, this.snow[i].y, 2, 2);
				}
			break;
		}

		if (this.game.getPlayer().time < 18000 || this.game.getPlayer().time > 64800)
		{
			if (this.game.getPlayer().time < 14400 || this.game.getPlayer().time > 72000)
			{
				this.game.getCtx().fillStyle = 'rgba(30, 29, 29, 0.6)';
    		} else
    		{
    			this.game.getCtx().fillStyle = 'rgba(30, 29, 29, 0.3)';
    		}
    		this.game.getCtx().fillRect(0, 0, this.game.getCanv().width, this.game.getCanv().height);
		}
	}
}

export {GameLive};