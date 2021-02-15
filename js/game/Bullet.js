class Bullet {
	constructor()
	{
		this.x      = 0;
		this.y      = 0;
		this.dx     = 0;
		this.dy     = 0;
		this.s      = 1000;
		this.dmg    = 0;
		this.w      = 4;
		this.h      = 4;
		this.active = false;
		this.froms  = {enemy: 0, player: 1}
		this.from   = undefined;
	}

	render(ctx)
	{
		if (this.active)
		{
			ctx.fillStyle = 'red';
			ctx.fillRect(this.x - this.w/2, this.y - this.h/2, this.w, this.h);
		}
	}
}

export {Bullet};