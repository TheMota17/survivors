class Camera {
	constructor(game)
	{
		this.game = game;

		this.viewWidth  = this.game.getCanv().width;
		this.viewHeight = this.game.getCanv().height;
		this.x          = 0;
		this.y          = 0;
	}

	update(dt)
	{
		this.x = this.game.getPlayer().x - this.viewWidth  / 2;
		this.y = this.game.getPlayer().y - this.viewHeight / 2;

		if (this.x <= 0) this.x = 0;
		if (this.x + this.viewWidth >= this.game.getWorld().getWidth()) this.x = this.game.getWorld().getWidth() - this.viewWidth;
		if (this.y <= 0) this.y = 0;
		if (this.y + this.viewHeight >= this.game.getWorld().getHeight()) this.y = this.game.getWorld().getHeight() - this.viewHeight;
	}
}

export {Camera};