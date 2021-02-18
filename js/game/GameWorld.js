class GameWorld {
	constructor(ctx, sprites, data)
	{	
		this.ctx          = ctx;

		this.sprites      = sprites;
		this.loc          = data.game.loc;
		this.loc_explored = data.game.loc_explored;
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