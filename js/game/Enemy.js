class Enemy {
	constructor(loc, data)
	{
		this.loc = loc;

		this.x     = data.x;
		this.y     = data.y;
		this.dx    = data.dx;
		this.dy    = data.dy;
		this.s     = data.s;
		this.nm    = data.nm;
		this.hp    = data.hp;
		this.die   = data.die;
		this.img   = data.img;
		this.timer = 3;
	}

	update(dt, bulletEmitter)
	{
		if (this.hp <= 0) 
		{
			this.die = true;
		} else 
		{

			if (this.timer >= 3) 
			{
				this.dx    = this.rand(-1, 1);
				this.dy    = this.rand(-1, 1);
				this.timer = 0;
			} else 
			{
				this.timer += dt;
			}

			if (this.x >= this.loc.width) 
			{
				this.dx = -1;
			} else if (this.x <= 0) 
			{
				this.dx = 1;
			}
			if (this.y >= this.loc.height)
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

	render(ctx)
	{
		ctx.fillStyle = 'red';
		ctx.fillText(this.nm, (this.x - ctx.measureText(this.nm).width/2), this.y - 16);

		ctx.fillStyle = 'black';
		ctx.fillRect((this.x - this.img.width/2) - 12, this.y - 14, 43, 3);
		ctx.fillStyle = 'green';
		ctx.fillRect((this.x - this.img.width/2) - 10, this.y - 14, this.hpPercent(this.hp), 1);

		ctx.drawImage(this.img, this.x - this.img.width/2, this.y - this.img.height/2);
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