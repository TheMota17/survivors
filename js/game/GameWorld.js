class GameWorld {
	constructor(ctx, sprites, loc, loc_explored)
	{	
		this.ctx          = ctx;

		this.sprites      = sprites;
		this.loc          = loc;
		this.loc_explored = loc_explored;
	}

	render()
	{
		this.ctx.drawImage(
			this.sprites['loc_' + this.loc],
			0,
			0,
			this.sprites['loc_' + this.loc].width,
			this.sprites['loc_' + this.loc].height,
			0,
			0,
			this.sprites['loc_' + this.loc].width,
			this.sprites['loc_' + this.loc].height
		);
	}
}

export {GameWorld};