class Camera {
	constructor(x, y, viewWidth, viewHeight) 
	{
		this.x          = x;
		this.y          = y;
		this.viewWidth  = viewWidth;
		this.viewHeight = viewHeight;
	}

	update(dt, playerX, playerY, worldWidth, worldHeight) 
	{
		this.x = playerX - this.viewWidth  / 2;
		this.y = playerY - this.viewHeight / 2;

		if (this.x <= 0) this.x = 0;
		if (this.x + this.viewWidth >= worldWidth) this.x = worldWidth - this.viewWidth;
		if (this.y <= 0) this.y = 0;
		if (this.y + this.viewHeight >= worldHeight) this.y = worldHeight - this.viewHeight;
	}
}

export {Camera};