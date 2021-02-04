class GameWorld {
	constructor(sprites, loc, loc_explored)
	{
		this.sprites      = sprites;
		this.loc          = loc;
		this.loc_explored = loc_explored;
	}

	render(ctx, camera)
	{
		ctx.drawImage(
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