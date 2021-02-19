class Enemy {
	constructor(game, data)
	{
		this.game  = game;

		this.x     = data.x;
		this.y     = data.y;
		this.dx    = data.dx;
		this.dy    = data.dy;
		this.s     = data.s;
		this.nm    = data.nm;
		this.hp    = data.hp;
		this.die   = data.die;
		this.img   = data.img;
		this.stateTimer = 3;
		this.atackTimer = 1;
	}

	update(dt)
	{
		if (this.atackTimer >= 1)
		{
			this.game.getBulletEmitter().activate(this.x, this.y, 'enemy');
			this.atackTimer = 0;
		} else 
		{
			this.atackTimer += dt;
		}

		if (this.hp <= 0)
		{
			this.die = true;
		} else 
		{

			if (this.stateTimer >= 3) 
			{
				this.dx    = this.rand(-1, 1);
				this.dy    = this.rand(-1, 1);
				this.stateTimer = 0;
			} else 
			{
				this.stateTimer += dt;
			}

			if (this.x >= this.game.getLoc().width) 
			{
				this.dx = -1;
			} else if (this.x <= 0) 
			{
				this.dx = 1;
			}
			if (this.y >= this.game.getLoc().height)
			{
				this.dy = -1;
			} else if (this.y <= 0)
			{
				this.dy = 1;
			}

			this.x += this.dx * (this.s * dt);
			this.y += this.dy * (this.s * dt);
		}
	}

	render()
	{
		this.game.getCtx().fillStyle = 'red';
		this.game.getCtx().fillText(this.nm, (this.x - this.game.getCtx().measureText(this.nm).width/2), this.y - 16);

		this.game.getCtx().fillStyle = 'black';
		this.game.getCtx().fillRect((this.x - this.img.width/2) - 12, this.y - 14, 43, 3);
		this.game.getCtx().fillStyle = 'green';
		this.game.getCtx().fillRect((this.x - this.img.width/2) - 10, this.y - 14, this.hpPercent(this.hp), 1);

		this.game.getCtx().drawImage(this.img, this.x - this.img.width/2, this.y - this.img.height/2);
	}

	takeDmg(dmg)
	{
		this.hp -= dmg;
	}

	hpPercent(hp)
	{
		return 40 * (hp / 100);
	}

	rand(min, max)
    {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
}

export {Enemy};