class Player {
	constructor(game, img, playerData)
	{
		this.game    = game;

		this.x       = playerData.x;
		this.y       = playerData.y
		this.s       = playerData.speed;
		this.hp      = playerData.hp;
		this.hung    = playerData.hung;
		this.thirst  = playerData.thirst;
		this.fatigue = playerData.fatigue;

		this.img     = img;
		this.width   = img.width;
		this.height  = img.height;

		this.left  = false;
		this.right = false;
		this.up    = false;
		this.down  = false;

		window.addEventListener('keydown', this.input.bind(this));
		window.addEventListener('keyup', this.input.bind(this));
		window.addEventListener('touchstart', this.input.bind(this));
		window.addEventListener('touchend', this.input.bind(this));
	}

	input(e)
	{
		switch(e.type[0])
		{
			case 't':
				switch(e.target.id)
				{
					case 'up_l':
						if (e.type == 'touchstart')
						{
							this.left = true;
							this.up = true;
						} else {
							this.left = false;
							this.up = false;
						}
					break;
					case 'up_r':
						if (e.type == 'touchstart')
						{
							this.right = true;
							this.up = true;
						} else {
							this.right = false;
							this.up = false;
						}
					break;
					case 'down_l':
						if (e.type == 'touchstart')
						{
							this.left = true;
							this.down = true;
						} else {
							this.left = false;
							this.down = false;
						}
					break;
					case 'down_r':
						if (e.type == 'touchstart')
						{
							this.right = true;
							this.down = true;
						} else {
							this.right = false;
							this.down = false;
						}
					break;
					case 'left':
						if (e.type == 'touchstart')
						{
							this.left = true;
						} else this.left = false;
						break;
					case 'up':
						if (e.type == 'touchstart')
						{
							this.up = true;
						} else this.up = false;
						break;
					case 'right':
						if (e.type == 'touchstart')
						{
							this.right = true;
						} else this.right = false;
						break;
					case 'down':
						if (e.type == 'touchstart')
						{
							this.down = true;
						} else this.down = false;
						break;
				}
			break;
			case 'k':
				if (e.keyCode == 37)
				{
					if (e.type == 'keydown')
					{
						this.left = true;
					} else this.left = false;
				}
				if (e.keyCode == 38)
				{
					if (e.type == 'keydown')
					{
						this.up = true;
					} else this.up = false;
				}
				if (e.keyCode == 39)
				{
					if (e.type == 'keydown')
					{
						this.right = true;
					} else this.right = false;
				}
				if (e.keyCode == 40)
				{
					if (e.type == 'keydown')
					{
						this.down = true;
					} else this.down = false;
				}
			break;
		}
	}

	update(dt)
	{
		if (this.timeToFire < this.maxTimeToFire)
		{
			this.timeToFire += dt;
		}

		if (this.left && 0 < (this.x - this.width/2))
		{
			this.x += -1 * (this.s * dt);
		}
		if (this.right && (this.x + this.width/2) < this.game.getLoc().width)
		{
			this.x += 1 * (this.s * dt);
		}
		if (this.up && 0 < (this.y - this.height/2))
		{
			this.y += -1 * (this.s * dt);
		}
		if (this.down && (this.y + this.height/2) < this.game.getLoc().height)
		{
			this.y += 1 * (this.s * dt);
		}
	}

	render()
	{
		this.game.getCtx().fillStyle = '#dac09c';
		this.game.getCtx().fillText('Вы', (this.x - this.game.getCtx().measureText('Вы').width/2), this.y - 16);

		this.game.getCtx().fillStyle = 'black';
		this.game.getCtx().fillRect((this.x - this.width/2) - 12, this.y - 14, 43, 3);
		this.game.getCtx().fillStyle = 'green';
		this.game.getCtx().fillRect((this.x - this.width/2) - 10, this.y - 14, this.hpPercent(), 1);

		this.game.getCtx().drawImage(this.img, 0, 0, this.width, this.height, this.x - this.width/2, this.y - this.height/2, this.width, this.height);
	}

	hpPercent()
	{
		return 40 * (this.hp / 100);
	}
}

export {Player};