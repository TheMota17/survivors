class GameLive {
	constructor(time, weather, temp, weatherTime) 
	{
		this.time    = time;
		this.weather = weather;
		this.temp    = temp;

		this.weatherTime = weatherTime;
	}

	update(dt)
	{
		if (this.time + (dt * 10) >= 86400) {
			let tempTime = (this.time + (dt * 10)) - 86400;
			this.time    = tempTime;
		} else {
			this.time += dt * 10;
		}

		this.weatherChange(dt);
	}

	render(ctx, canv)
	{
		if (this.time < 18000 || this.time > 64800) 
		{
			if (this.time < 14400 || this.time > 72000) 
			{
				ctx.fillStyle = 'rgba(30, 29, 29, 0.6)';
    		} else 
    		{
    			ctx.fillStyle = 'rgba(30, 29, 29, 0.3)';
    		}
    		ctx.fillRect(0, 0, canv.width, canv.height);
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