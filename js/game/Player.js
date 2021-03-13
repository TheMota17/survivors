class Player {
	constructor(game, playerData)
	{
		this.game       = game;

		this.x          = Number(playerData.x);
		this.y          = Number(playerData.y);
		this.timeToStep = 0;
		this.hp         = playerData.hp;
		this.hung       = playerData.hung;
		this.thirst     = playerData.thirst;
		this.fatigue    = playerData.fatigue;

		this.time       = Number(playerData.time);
		this.weather    = Number(playerData.weather);
		this.temp       = Number(playerData.temp);

		this.loc          = playerData.loc;
		this.loc_explored = playerData.loc_explored;

		this.width   = 40;
		this.height  = 40;

		this.game.getGameBtns().addEventListener('click', this.input.bind(this));
	}

	input(e)
	{
		switch(e.target.id)
		{
			case 'left':
				if (this.stepTime() && (this.x - 40) >= 0)
				{
					this.changeCoordinates('left');
					this.sendPoint('left');
					this.stepTimeChange();
				}
				break;
			case 'up':
				if (this.stepTime() && (this.y - 40) >= 0)
				{
					this.changeCoordinates('up');
					this.sendPoint('up');
					this.stepTimeChange();
				}
				break;
			case 'right':
				if (this.stepTime() && (this.x + 40) < this.game.getWorld().getWidth())
				{
					this.changeCoordinates('right');
					this.sendPoint('right');
					this.stepTimeChange();
				}
				break;
			case 'down':
				if (this.stepTime() && (this.y + 40) < this.game.getWorld().getHeight())
				{
					this.changeCoordinates('down');
					this.sendPoint('down');
					this.stepTimeChange();
				}
				break;
		}
	}

	stepTime()
	{
		if (this.timeToStep <= 0) return true;
	}

	stepTimeChange()
	{
		this.timeToStep = 0.3;
	}

	changeCoordinates(dir)
	{
		switch(dir)
		{
			case 'left':
				this.x -= 40;
				break;
			case 'up':
				this.y -= 40;
				break;
			case 'right':
				this.x += 40;
				break;
			case 'down':
				this.y += 40;
				break;
		}
	}

	sendPoint(dir)
	{
		let params = new FormData();
        params.append('token', localStorage.getItem('token'));
		axios.post('/core/Game/?action=' + dir, params)
	}

	update(dt)
	{
		if (this.timeToStep > 0)
		{
			this.timeToStep -= dt;
		}
	}

	render()
	{
		this.game.getCtx().fillStyle = '#dac09c';
		this.game.getCtx().fillText('Вы', ((this.x - this.game.getCtx().measureText('Вы').width/2) + this.width/2), this.y + 2);

		this.game.getCtx().fillStyle = 'black';
		this.game.getCtx().fillRect(this.x - 3, this.y + 5, 43, 3);
		this.game.getCtx().fillStyle = 'green';
		this.game.getCtx().fillRect(this.x - 1, this.y + 5, this.hpPercent(), 1);

		this.game.getCtx().beginPath();
			this.game.getCtx().arc(this.x + this.width/2, this.y + this.height/2, 10, 0, 2 * Math.PI, false);
			this.game.getCtx().fillStyle = 'rgb(52, 49, 42)';
			this.game.getCtx().fill();
		this.game.getCtx().stroke();
	}

	hpPercent()
	{
		return 40 * (this.hp / 100);
	}
}

export {Player};