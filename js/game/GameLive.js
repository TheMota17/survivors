class GameLive {
	constructor(game)
	{
		this.ctx         = game.ctx;
		this.canv        = game.canv;

		this.time        = game.data.game.time;
		this.weather     = game.data.game.weather;
		this.temp        = game.data.game.temp;
		this.weatherTime = game.data.game.weatherTime;

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
		switch(this.weather)
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

		if (this.time + (dt * 10) >= 86400) {
			let tempTime = (this.time + (dt * 10)) - 86400;
			this.time    = tempTime;
		} else {
			this.time += dt * 10;
		}

		this.weatherChange(dt);
	}

	render()
	{
		switch(this.weather) 
		{
			case 2:
			case 3:
				for(let i = 0; i < this.rain.length; i++)
				{
					this.ctx.fillStyle = 'white';
					this.ctx.fillRect(this.rain[i].x, this.rain[i].y, 1, 1);
				}
			break;
			case 5:
				for(let i = 0; i < this.snow.length; i++)
				{
					this.ctx.fillStyle = 'white';
					this.ctx.fillRect(this.snow[i].x, this.snow[i].y, 2, 2);
				}
			break;
		}

		if (this.time < 18000 || this.time > 64800) 
		{
			if (this.time < 14400 || this.time > 72000) 
			{
				this.ctx.fillStyle = 'rgba(30, 29, 29, 0.6)';
    		} else 
    		{
    			this.ctx.fillStyle = 'rgba(30, 29, 29, 0.3)';
    		}
    		this.ctx.fillRect(0, 0, this.canv.width, this.canv.height);
		}
	}

	weatherChange(dt) 
	{
		if (this.weatherTime >= 1800) 
		{
			let rand = Math.floor(Math.random() * (2 - 1 + 1)) + 1;

			if (rand == 2) 
			{
				let weathers;
				switch(this.weather) 
				{
	                case 1:
						weathers = [2, 4];
	                    break;
	                case 2:
	                    weathers = [1, 3];
	                    break;
	                case 3:
	                    weathers = [1, 2];
	                    break;
	                case 4:
	                    weathers = [2, 5];
	                    break;
	                case 5:
	                    weathers = [1, 4];
	                    break;
                }

                let rand_weather = Math.floor(Math.random() * (1 - 0 + 1)) + 0;
                this.weather     = weathers[ rand_weather ];

                this.tempChange();
			}
			this.weatherTime = 0;
		} else {
			this.weatherTime += dt * 10;
		}
	}

	tempChange()
	{
		let temps;
		switch(this.weather) {
            case 1:
                temps = [1, 2];
                break;
            case 2:
                temps = [2, 3];
                break;
            case 3:
                temps = [3, 4];
                break;
            case 4:
                temps = [3, 3];
                break;
            case 5:
                temps = [3, 4];
                break;
        }

        let rand_temp = Math.floor(Math.random() * (1 - 0 + 1)) + 0;
        this.temp     = temps[ rand_temp ];
	}
}

export {GameLive};