class Enemy {
	constructor(data)
	{
		this.x   = data.x;
		this.y   = data.y;
		this.dx  = data.dx;
		this.dy  = data.dy;
		this.s   = data.s;
		this.nm  = data.nm;
		this.hp  = data.hp;
		this.die = data.die;
		this.img = data.img;
	}

	update(dt)
	{
		
	}

	takeDmg()
	{
		this.hp -= 10;
	}
}

export {Enemy};