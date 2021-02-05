class Camera {
	constructor(canv, x, y, loc, player) 
	{
		this.x          = x;
		this.y          = y;
		this.viewWidth  = canv.width;
		this.viewHeight = canv.height;
		this.loc        = loc;
		this.player     = player;
	}

	update(dt, worldWidth, worldHeight)
	{
		this.x = this.player.x - this.viewWidth  / 2;
		this.y = this.player.y - this.viewHeight / 2;

		if (this.x <= 0) this.x = 0;
		if (this.x + this.viewWidth >= this.loc.width) this.x = this.loc.width - this.viewWidth;
		if (this.y <= 0) this.y = 0;
		if (this.y + this.viewHeight >= this.loc.height) this.y = this.loc.height - this.viewHeight;
	}
}

export {Camera};