class GameWorld {
	constructor(sprites, loc, loc_explored, temp)
	{
		this.sprites      = sprites;
		this.loc          = loc;
		this.loc_explored = loc_explored;
		this.objs         = [
			{x: 200, y: 200, item: 1},
		];
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